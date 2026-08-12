<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Back\Imports;
use App\Models\Back\Lineage;
use App\Models\Back\Snacks;
use App\Models\Back\Payment;
use App\Models\Back\Supplement;
use App\Traits\GetLineage;
use App\Traits\IncomeStatements;

class ImportProduct extends Controller {
  use IncomeStatements, GetLineage;
  public function index(Request $request) {
    $imports = Imports::with("employee")->get();
    $lineageImport = $this->getArray(Lineage::class, "input", false);
    $lineageImport = $this->getArray(Lineage::class, "input", false);
    $lineageInput = $this->get(Lineage::class, "input", false);
    $lineageRevenues = $this->get(Lineage::class, "revenues", false);
    $supplementsPayment = Payment::where("type", "supplement")->with("employee")->get();
    $supplements = Supplement::with([
      'imports' => function($q) {
        $q->where('type', 'supplement');
      },
      'payment',
    ])->get();
    $lineagesByName = [];
    $lineagesByPayment = [];
    foreach ($supplements as $supplement) {
      $lineagesByName[$supplement->name] = $this->getArray(Lineage::class, $supplement->name, false);
      $supplementsPayments = Payment::where("type", "supplement")->where("order_name", $supplement->name)->get();
      foreach ($supplementsPayments as $p) {
        $key = $p->order_name;
        if (isset($lineagesByPayment[$key])) {
          $lineagesByPayment[$key] += (int) $p->paid;
        } else {
          $lineagesByPayment[$key] = (int) $p->paid;
        };
      };
    };
    return view('Company.Dashboard.Pages.imports', compact("imports", "supplementsPayment", "supplements", "lineagesByName", "lineagesByPayment", "lineageImport", "lineageInput", "lineageRevenues"));
  }
  public function addProduct(Request $request) {
    $request->validate([
      'name_product' => ['required', 'string'],
      'description' => ['nullable', 'string'],
      'price' => ['required', 'integer'],
      'quantity' => ['required', 'integer'],
      'type' => ['required', Rule::in(['snacks', 'supplement'])],
      'img' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    ]);
    $rand = rand(100000, time());
    $randProduct = rand(100000, time());
    $nameLower = mb_strtolower($request->input("name_product"));
    $name = $request->input("name_product");
    $amount = $request->input("price");
    $quantity = $request->input("quantity");
    $searchProduct = Imports::whereRaw('LOWER(name) = ?', "$nameLower")->first();
    $typeProduct = $request->input("type");
    $employee = Auth::guard('employee')->user();
    if ($searchProduct) {
      $searchProduct->amount = $amount;
      $searchProduct->quantity = $quantity;
      $searchProduct->save();
      $this->addStatements($searchProduct->name, "input", "Expenses", $amount);
      Lineage::addLineage(null, null, $searchProduct->code, null, null, "input", 1);
      Lineage::addLineage(null, null, null, $searchProduct->code, null, "Expenses", 1);
    } else {
      $imports = new Imports();
      $imports->code = $rand;
      $imports->code_employee = $employee->code;
      $imports->name = $name;
      $imports->state = "import";
      $imports->quantity = $quantity;
      $imports->amount = $amount;
      $imports->save();
      $this->addStatements($name, "input", "Expenses", $amount);
      Lineage::addLineage(null, null, $imports->code, null, null, "input", 1);
      Lineage::addLineage(null, null, null, $imports->code, null, "Expenses", 1);
      if ($typeProduct == "supplement") {
        $request->validate([
          'img' => ['required', 'mimes:png,jpg,jpeg', 'max:5120'],
        ]);
        $randSupplement = rand(100000, time());
        $supplement = new Supplement();
        $supplement->code = $randProduct;
        $supplement->name = $request->input("name_product");
        $supplement->description = (string) $request->input("description");
        if ($request->has("img")) {
          date_default_timezone_set("Africa/Cairo");
          $d = date_create();
          $new_name = date_format($d, "Y-m-j_g-i_A") . "." . $request->img->extension();
          $request->img->move(public_path("images/products"), $new_name);
          $supplement->img = $new_name;
        };
        $supplement->amount = $amount;
        $supplement->save();
        $imports->code_supplements = $randProduct;
        $imports->type = "supplement";
        $imports->save();
      } else {
        $randSnacks = rand(100000, time());
        $snacks = new Snacks();
        $snacks->code = $randProduct;
        $snacks->name = $request->input("name_product");
        $snacks->amount = $amount;
        $snacks->save();
        $imports->code_snaks = $randProduct;
        $imports->type = "snacks";
        $imports->save();
      };
    };
    return back();
  }
  public function destroySupplementsAndImports(Request $request) {
    $request->validate([
      'code' => ['required', 'string'],
    ]);
    $supplement = Supplement::where("code", $request->input("code"))->first();
    $imports = Imports::where("code_supplements", $request->input("code"))->first();
    $dir = public_path("images/products/" . $supplement->img);
    if (File::exists($dir) && is_dir($dir)) {
      File::delete($dir);
    };
    $supplement->delete();
    $imports->delete();
    return back();
  }
}
