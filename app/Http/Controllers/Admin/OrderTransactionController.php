<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class OrderTransactionController extends Controller
{
    /**
     * Daftar riwayat transaksi dengan filter rentang waktu, pencarian, dan statistik agregat.
     */
    public function index(Request $request): View
    {
        $period = $request->input('period', 'today');
        $search = $request->input('search', '');

        [$startDate, $endDate] = $this->resolveDateRange($period);

        $query = Order::query()
            ->with(['items.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at');

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('queue_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        // --- Statistik Agregat ---
        $statsBase = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', '!=', 'cancelled');

        if ($search !== '') {
            $statsBase->where(function ($q) use ($search): void {
                $q->where('queue_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $totalRevenue = (float) (clone $statsBase)->sum('total_amount');
        $totalOrders = (int) (clone $statsBase)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0.0;

        $paidOrders = (clone $statsBase)
            ->with('items')
            ->where('payment_status', 'paid')
            ->get();
        $totalOmzet = (float) $paidOrders->sum('total_amount');
        $totalModal = (float) $paidOrders->sum(
            fn (Order $order): float => (float) $order->items->sum(
                fn ($item): float => ((float) ($item->cost_price ?? 0)) * (int) $item->quantity
            )
        );
        $totalKeuntungan = $totalOmzet - $totalModal;
        $marginPersen = $totalOmzet > 0
            ? round(($totalKeuntungan / $totalOmzet) * 100, 1)
            : 0;

        $qrisCount = (int) (clone $statsBase)->where('payment_method', 'qris')->count();
        $cashCount = (int) (clone $statsBase)->where('payment_method', 'cash')->count();
        $paidCount = $qrisCount + $cashCount;
        $qrisPct = $paidCount > 0 ? round(($qrisCount / $paidCount) * 100) : 0;
        $cashPct = $paidCount > 0 ? (100 - $qrisPct) : 0;

        $topPaymentMethod = $qrisCount >= $cashCount ? 'QRIS' : 'Cash';

        return view('admin.orders.index', [
            'orders' => $orders,
            'period' => $period,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalKeuntungan' => $totalKeuntungan,
            'marginPersen' => $marginPersen,
            'totalOrders' => $totalOrders,
            'avgOrderValue' => $avgOrderValue,
            'qrisPct' => $qrisPct,
            'cashPct' => $cashPct,
            'topPaymentMethod' => $topPaymentMethod,
        ]);
    }

    /**
     * Ekspor laporan riwayat transaksi dan penjualan ke format PDF resmi.
     */
    public function exportPdf(Request $request): Response
    {
        $period = $request->input('period', 'today');
        $search = $request->input('search', '');

        [$startDate, $endDate] = $this->resolveDateRange($period);

        $query = Order::query()
            ->with(['items.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at');

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('queue_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->get();

        $orders->each(function (Order $order): void {
            $order->total_hpp = (float) $order->items->sum(
                fn ($item): float => ((float) ($item->cost_price ?? 0)) * (int) $item->quantity
            );
            $order->total_profit = (float) $order->total_amount - $order->total_hpp;
        });

        // --- Statistik Agregat ---
        $summaryOrders = $orders->filter(
            fn (Order $order): bool => $order->order_status !== 'cancelled'
        );

        $totalRevenue = (float) $summaryOrders->sum('total_amount');
        $totalHpp = (float) $summaryOrders->sum('total_hpp');
        $totalKeuntungan = $totalRevenue - $totalHpp;
        $marginPersen = $totalRevenue > 0
            ? round(($totalKeuntungan / $totalRevenue) * 100, 1)
            : 0.0;
        $totalOrders = (int) $summaryOrders->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0.0;

        $qrisCount = (int) $summaryOrders->where('payment_method', 'qris')->count();
        $cashCount = (int) $summaryOrders->where('payment_method', 'cash')->count();
        $paidCount = $qrisCount + $cashCount;
        $qrisPct = $paidCount > 0 ? round(($qrisCount / $paidCount) * 100) : 0;
        $cashPct = $paidCount > 0 ? (100 - $qrisPct) : 0;

        $topPaymentMethod = $qrisCount >= $cashCount ? 'QRIS' : 'Cash';

        $periodLabels = [
            'today' => 'Hari Ini',
            'this_week' => 'Minggu Ini',
            'this_month' => 'Bulan Ini',
            'this_year' => 'Tahun Ini',
            '5_years' => '5 Tahun Terakhir',
        ];
        $periodLabel = $periodLabels[$period] ?? 'Hari Ini';

        $pdf = Pdf::loadView('admin.reports.transaction_pdf', [
            'orders' => $orders,
            'period' => $period,
            'periodLabel' => $periodLabel,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalHpp' => $totalHpp,
            'totalKeuntungan' => $totalKeuntungan,
            'marginPersen' => $marginPersen,
            'totalOrders' => $totalOrders,
            'avgOrderValue' => $avgOrderValue,
            'qrisPct' => $qrisPct,
            'cashPct' => $cashPct,
            'topPaymentMethod' => $topPaymentMethod,
        ])->setPaper('a4', 'portrait');

        $filename = 'Laporan_Transaksi_Engineering_Cafe_'.$period.'_'.now()->format('Ymd_His').'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Perbarui status pembayaran pesanan (paid atau unpaid).
     */
    public function updatePaymentStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'string', 'in:paid,unpaid,pending,failed'],
        ]);

        $order->update([
            'payment_status' => $validated['payment_status'],
            'paid_at' => $validated['payment_status'] === 'paid' ? now() : null,
        ]);

        return back()->with('success', "Status pembayaran pesanan {$order->queue_number} berhasil diperbarui!");
    }

    /**
     * Nonaktifkan riwayat transaksi pesanan menggunakan soft delete.
     * Data tidak dihapus permanen dari database, hanya disembunyikan dari laporan dan omzet.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $queueNumber = $order->queue_number;
        $customerName = $order->customer_name;

        $order->delete();

        return back()->with('success', "Riwayat transaksi {$queueNumber} milik {$customerName} berhasil dinonaktifkan.");
    }

    /**
     * Resolusi rentang tanggal berdasarkan filter periode yang dipilih.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolveDateRange(string $period): array
    {
        $now = Carbon::now();

        return match ($period) {
            'this_week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'this_year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            '5_years' => [Carbon::now()->subYears(5)->startOfYear(), Carbon::now()->endOfYear()],
            default => [$now->copy()->startOfDay(), $now->copy()->endOfDay()], // 'today'
        };
    }
}
