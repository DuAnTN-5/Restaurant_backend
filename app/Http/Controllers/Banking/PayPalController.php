<?php

namespace App\Http\Controllers\Banking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use App\Models\Reservation;
use Flasher\Prime\FlasherInterface;

class PayPalController extends Controller
{
    private $apiContext;

    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
            )
        );
        $this->apiContext->setConfig(config('paypal.settings'));
    }

    // Tạo thanh toán
    public function createPayment($reservationId, $amount)
{
    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    $usdAmount = $amount / 23000; // Quy đổi từ VND sang USD

    $amountObj = new Amount();
    $amountObj->setCurrency("USD")->setTotal($usdAmount);

    $transaction = new Transaction();
    $transaction->setAmount($amountObj)->setDescription("Cọc đặt bàn cho reservation #$reservationId");

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl(route('payment.success', ['reservationId' => $reservationId]))
                 ->setCancelUrl(route('payment.cancel'));

    $payment = new Payment();
    $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions([$transaction]) // Đảm bảo transactions là một mảng
            ->setRedirectUrls($redirectUrls);

    try {
        $payment->create($this->apiContext);
        return redirect($payment->getApprovalLink());
    } catch (\Exception $ex) {
        return back()->withError('Có lỗi xảy ra trong quá trình thanh toán: ' . $ex->getMessage());
    }
}


    // Xử lý khi thanh toán thành công
    public function executePayment(Request $request, FlasherInterface $flasher)
    {
        $paymentId = $request->paymentId;
        $payerId = $request->PayerID;
        $reservationId = $request->get('reservationId'); // Lấy reservationId từ URL

        // Lấy thông tin thanh toán từ PayPal
        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            // Thực hiện thanh toán
            $result = $payment->execute($execution, $this->apiContext);

            // Cập nhật trạng thái đặt bàn sau khi thanh toán thành công
            $reservation = Reservation::findOrFail($reservationId);
            $reservation->update(['status' => 'paid']);

            $flasher->addSuccess('Thanh toán thành công. Đặt bàn đã được xác nhận.');
            return redirect()->route('reservations.index');
        } catch (\Exception $ex) {
            $flasher->addError('Có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.');
            return back();
        }
    }

    // Xử lý khi thanh toán bị hủy
    public function cancelPayment(FlasherInterface $flasher)
    {
        $flasher->addError('Thanh toán đã bị hủy.');
        return redirect()->route('reservations.index');
    }
}
