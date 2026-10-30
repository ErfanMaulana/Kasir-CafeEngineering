<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

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

        $totalRevenue = (float) (clone $statsBase)->sum('total_amount');
        $totalOrders = (int) (clone $statsBase)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0.0;

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
            'totalOrders' => $totalOrders,
            'avgOrderValue' => $avgOrderValue,
            'qrisPct' => $qrisPct,
            'cashPct' => $cashPct,
            'topPaymentMethod' => $topPaymentMethod,
        ]);
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
