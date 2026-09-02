<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Front\Client;
use App\Models\Back\RequestsPayment;
use App\Models\Back\Payment;
use App\Models\Back\PaymentRegistry;
use App\Models\Back\Imports;
use App\Models\Back\Lineage;
use App\Traits\IncomeStatements;
use Carbon\Carbon;

trait Payments {
  use IncomeStatements;
  public function registration($codeClient, $attachment, $amount) {
    return DB::transaction(function () use ($codeClient, $attachment, $amount) {
      $employee = Auth::guard('employee')->user();
      $paymentsClient = null;
      $matchedCode = null;
      if ($codeClient) {
        $query = Payment::where('code_client', $codeClient);
        if (ctype_digit($attachment)) {
          $query->where(function($q) use ($attachment) {
            $q->where('code', $attachment)
              ->orWhere('code_supplements', $attachment)
              ->orWhere('code_systems', $attachment)
              ->orWhere(function($sub) use ($attachment) {
                $sub->where('code_snacks', $attachment)->where('paid', 0);
              });
          });
        } else {
          $query->where('type', $attachment);
        };
        $paymentsClient = $query->lockForUpdate()->first();
        if ($paymentsClient) {
          if (ctype_digit($attachment)) {
            if (isset($paymentsClient->code) && $paymentsClient->code == $attachment) {
              $matchedCode = $paymentsClient->code;
            } elseif (isset($paymentsClient->code_supplements) && $paymentsClient->code_supplements == $attachment) {
              $matchedCode = $paymentsClient->code_supplements;
            } elseif (isset($paymentsClient->code_systems) && $paymentsClient->code_systems == $attachment) {
              $matchedCode = $paymentsClient->code_systems;
            } elseif (isset($paymentsClient->code_snacks) && $paymentsClient->code_snacks == $attachment) {
              $matchedCode = $paymentsClient->code_snacks;
            };
          } else {
            if (isset($paymentsClient->type) && $paymentsClient->type == $attachment) {
              $matchedCode = $paymentsClient->type;
            };
          };
        };
      };
      if ($matchedCode && $matchedCode !== null) {
        $requestsPayment = RequestsPayment::where("code_client", $codeClient)->where("code", $paymentsClient->code_request_payment)->where("state", "acceptance")->first();
        if ($requestsPayment) {
          $payments = Payment::where(function($q) use ($matchedCode) {
            $q->where('code', $matchedCode)
              ->orWhere('code_supplements', $matchedCode)
              ->orWhere('code_systems', $matchedCode)
              ->orWhere(function($sub) use ($matchedCode) {
                $sub->where('code_snacks', $matchedCode)->where('paid', 0);
              });
          })->where(function($q) {
            $q->whereColumn('amount', '>', 'paid')
              ->orWhere('payday', 'daily');
          })->lockForUpdate()->first();
          if ($payments) {
            $date = strtolower(Carbon::now('Africa/Cairo')->format('F'));
            $currentPayMonth = isset($payments->paymonth) ? strtolower((string)$payments->paymonth) : null;
            if ($currentPayMonth === $date) {
              $rand = rand(100000, time());
              $paymentRegistry = new PaymentRegistry();
              $paymentRegistry->code = $rand;
              $paymentRegistry->order_name = $payments->order_name;
              $paymentRegistry->type = $payments->type;
              $paymentRegistry->amount = $amount;
              $paymentRegistry->paymonth = $date;
              $paymentRegistry->code_payments = $payments->code;
              $paymentRegistry->code_employee = $employee->code;
              $paymentRegistry->save();
              $payments->paid += $amount;
              $payments->save();
              $this->addStatements($payments->order_name, $payments->type, "Revenues", $amount);
              Lineage::addLineage($requestsPayment->code_supplements, $requestsPayment->code_systems, null, null, null, $payments->type, 1);
            } else {
              $payments->paymonth = $date;
              $payments->paid = 0;
              $payments->save();
              $rand = rand(100000, time());
              $paymentRegistry = new PaymentRegistry();
              $paymentRegistry->code = $rand;
              $paymentRegistry->order_name = $payments->order_name;
              $paymentRegistry->type = $payments->type;
              $paymentRegistry->amount = $amount;
              $paymentRegistry->paymonth = $date;
              $paymentRegistry->code_payments = $payments->code;
              $paymentRegistry->code_employee = $employee->code;
              $paymentRegistry->save();
              $payments->paid += $amount;
              $payments->save();
              $this->addStatements($payments->order_name, $payments->type, "Revenues", $amount);
              Lineage::addLineage($requestsPayment->code_supplements, $requestsPayment->code_systems, null, null, null, $payments->type, 1);
            };
          } else {
            return back()->withErrors(['Payment' => __('messages.payment-invoice')]);
          };
        } else {
          return back()->withErrors(['RequestsPayment' => __('messages.no-installments')]);
        };
      };
    });
  }
}