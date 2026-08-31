<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Http\Middleware\AuthenticatedToCompany;
use App\Http\Middleware\CustomerVerification;
use App\Http\Middleware\CheckAdmin;
use App\Http\Controllers\Website\web\Pages\ArticleController;
use App\Http\Controllers\Website\web\Pages\ContactUsController;
use App\Http\Controllers\Website\web\Pages\PrivacyPolicyController;
use App\Http\Controllers\Website\web\Pages\StoreController;
use App\Http\Controllers\Website\web\Pages\PackagesController;
use App\Http\Controllers\Website\web\Pages\CustomerRequestsController;
use App\Http\Controllers\Website\web\Pages\WelcomeController;
use App\Http\Controllers\Website\web\Pages\LoginController;
use App\Http\Controllers\Website\web\Pages\QrLoginController;
use App\Http\Controllers\Website\Dashboard\Pages\CoachController;
use App\Http\Controllers\Website\Dashboard\Pages\ClientQr;
use App\Http\Controllers\Website\Dashboard\Pages\Dashboard;
use App\Http\Controllers\Website\Dashboard\Pages\SchedulesExercises;
use App\Http\Controllers\Website\Dashboard\Pages\Plans;
use App\Http\Controllers\Website\Dashboard\Pages\Health;
use App\Http\Controllers\Website\Dashboard\Pages\BurnMeter;
use App\Http\Controllers\Website\Dashboard\Pages\SupplementsClient;
use App\Http\Controllers\Website\Dashboard\Pages\SettingClients;
use App\Http\Controllers\Website\web\Pages\Articles;
use App\Http\Controllers\Company\web\Pages\LoginCompany;
use App\Http\Controllers\Company\Dashboard\Pages\Employees;
use App\Http\Controllers\Company\Dashboard\Pages\DashboardCompany;
use App\Http\Controllers\Company\Dashboard\Pages\Users;
use App\Http\Controllers\Company\Dashboard\Pages\Historys;
use App\Http\Controllers\Company\Dashboard\Pages\Analytics;
use App\Http\Controllers\Company\Dashboard\Pages\Records;
use App\Http\Controllers\Company\Dashboard\Pages\Requests;
use App\Http\Controllers\Company\Dashboard\Pages\ContactUs;
use App\Http\Controllers\Company\Dashboard\Pages\Exercises;
use App\Http\Controllers\Company\Dashboard\Pages\Publications;
use App\Http\Controllers\Company\Dashboard\Pages\ImportProduct;
use App\Http\Controllers\Company\Dashboard\Pages\SettingCompanys;
use App\Http\Controllers\Company\Dashboard\Pages\Coach;
use App\Http\Controllers\Company\Dashboard\Pages\Customers;
use App\Http\Controllers\Company\Dashboard\Pages\ClientQrScan;
use App\Http\Controllers\LogOut;

Route::controller(Articles::class)->group(function () {
  Route::get('/main-articles', 'mainArticles')->name("mainArticles");
});

Route::controller(WelcomeController::class)->group(function () {
  Route::get('/', 'front')->name("front");
});

Route::controller(LoginController::class)->group(function () {
  Route::get('/login-page', 'loginPage')->name("loginPage");
  Route::post('/sign-up', 'signUp')->name("signUp");
  Route::post('/login-in', 'login')->name("client.login");
  Route::post('/forget', 'forget')->name("client.forget")->middleware('throttle:5,1');
  Route::post('/verify-code', 'verifyCode')->name("client.verifyCode")->middleware('throttle:5,1');
  Route::post('/reset-password', 'resetPassword')->name("client.resetPassword")->middleware('throttle:5,1');
});

Route::controller(QrLoginController::class)->group(function () {
  Route::post('/qr-login', 'login')->name('qr.login');
});

Route::controller(ArticleController::class)->group(function () {
  Route::get('/about-us', 'article')->name("aboutUs");
  Route::redirect('/article', '/about-us');
});

Route::controller(ContactUsController::class)->group(function () {
  Route::get('/contact-us', 'index')->name("contactUs");
  Route::post('/contact-us', 'store')->name("contactUsStore");
});

Route::controller(PrivacyPolicyController::class)->group(function () {
  Route::get('/privacy-policy', 'privacyPolicy')->name("privacyPolicy");
});

Route::controller(StoreController::class)->group(function () {
  Route::get('/stores', 'stores')->name("stores");
});

Route::controller(PackagesController::class)->group(function () {
  Route::get('/packages', 'packages')->name("packages");
});

Route::controller(CustomerRequestsController::class)->group(function () {
  Route::post('/add-request-product', 'addRequestProduct')->name("addRequestProduct");
  Route::post('/add-request-customer', 'addRequestCustomer')->name("addRequestCustomer");
  Route::post('/delete-request-customer', 'deleteCustomerRequests')->name("deleteCustomerRequests");
});

Route::middleware(CustomerVerification::class)->group(function () {
  Route::controller(Dashboard::class)->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard');
    Route::post('/search_client', 'search')->name('search');
    Route::post('/search_img', 'searchImg')->name('searchImg');
  });

  Route::controller(Health::class)->group(function () {
    Route::get('/health', 'index')->name('health');
    Route::post('/lineage', 'addLineagesClient')->name('addLineagesClient');
    Route::post('/save-img', 'saveImgInBody');
  });

  Route::controller(SchedulesExercises::class)->group(function () {
    Route::get('/schedule', 'index')->name('schedule');
    Route::post('/get-exercises', 'getExercises')->name('getExercises');
    Route::post('/insert-pay-day', 'insertExerciseDay')->name('insertExerciseDay');
    Route::post('/register-exercise', 'registerExercise');
    Route::get('/registrations-week', 'getRegistrationsWeek');
  });

  Route::controller(Plans::class)->group(function () {
    Route::get('/plans', 'index')->name('plans');
  });

  Route::controller(BurnMeter::class)->group(function () {
    Route::get('/burn-meter', 'index')->name('burnMeter');
    Route::post('/save-data-cardio', 'saveDataCardio')->name('saveDataCardio');
  });

  Route::controller(SupplementsClient::class)->group(function () {
    Route::get('/supplement-store', 'index')->name('supplementStore');
  });

  Route::controller(SettingClients::class)->group(function () {
    Route::get('/settings', 'index')->name('settings');
    Route::post('/update-profile', 'updateProfile')->name('updateProfile');
  });

  Route::controller(CoachController::class)->group(function () {
    Route::get('/coach', 'index')->name('coach');
    Route::post('/coach/request', 'requestCoach')->name('coach.request');
    Route::post('/coach/cancel', 'cancelCoach')->name('coach.cancel');
  });

  Route::controller(ClientQr::class)->group(function () {
    Route::get('/my-qr', 'index')->name('myQr');
    Route::post('/my-qr/rotate', 'rotate')->name('myQr.rotate');
  });

  Route::controller(LogOut::class)->group(function () {
    Route::post('/log-out-client', 'logOutClient')->name('logOutClient');
  });
});

Route::controller(LoginCompany::class)->group(function () {
  Route::get('/login-company', 'index')->name('loginCompany');
  Route::post('/login', 'login')->name('login');
  Route::post('/forget-pass', 'forget')->name('company.forget');
});

Route::middleware(AuthenticatedToCompany::class)->group(function () {
  Route::controller(Employees::class)->group(function () {
    Route::post('/add-employees', 'addEmployee')->name('addEmployee');
  });

  Route::controller(DashboardCompany::class)->group(function () {
    Route::get('/dashboard-company', 'index')->name('dashboardCompany');
    Route::post('/search', 'search')->name('search');
    Route::post('/search-img', 'searchImg')->name('searchImg');
    Route::post('/count-request', 'eventCountRequest')->name('eventCountRequest');
  });

  Route::middleware(CheckAdmin::class)->group(function () {
    Route::controller(Users::class)->group(function () {
      Route::get('/users', 'index')->name('users');
      Route::post('/update-employee', 'updateEmployee')->name('updateEmployee');
      Route::post('/update-client', 'updateClient')->name('updateClient');
      Route::post('/destroy', 'destroy')->name('destroy');
      Route::post('/get-all-data-client', 'getAllDataClient')->name('getAllDataClient');
        Route::post('/users/regenerate-barcode', 'regenerateBarcode')->name('users.regenerateBarcode');
        Route::post('/users/revoke-barcode', 'revokeBarcode')->name('users.revokeBarcode');
        Route::get('/users/print-barcode/{code}', 'printBarcode')->name('users.printBarcode');
      });

    Route::controller(Historys::class)->group(function () {
      Route::get('/history', 'index')->name('history');
    });

    Route::controller(Analytics::class)->group(function () {
      Route::get('/analytics', 'index')->name('analytics');
    });
  });

  Route::controller(Records::class)->group(function () {
    Route::get('/records', 'index')->name('records');
    Route::post('/search-client', 'searchClient')->name('searchClient');
    Route::post('/record', 'record')->name('record');
    Route::post('/recordExit', 'recordExit')->name('recordExit');
    Route::post('/add-requests', 'addRequests')->name('addRequests');
    Route::post('/get-supplement-client', 'getSupplementClient')->name('getSupplementClient');
    Route::post('/registration-requests-payment', 'registrationRequestsPayment')->name('registrationRequestsPayment');
    Route::post('/sign', 'signUp')->name('signUpRecord');
    Route::post('/get-payment-customer', 'getPaymentCustomer')->name('getPaymentCustomer');
  });

  Route::controller(Requests::class)->group(function () {
    Route::get('/requests', 'index')->name('requests');
    Route::post('/add-payments', 'addPayments')->name('addPayments');
    Route::post('/customer-requests', 'customerRequests')->name('customerRequests');
  });

  Route::controller(ContactUs::class)->group(function () {
    Route::get('/contact-us-company', 'index')->name("contactUsCompany");
    Route::post('/destroy-contact', 'destroy')->name("destroyContact");
  });

  Route::controller(Exercises::class)->group(function () {
    Route::get('/exercise', 'index')->name('exercise');
    Route::post('/add-exercises', 'addExercises')->name('addExercises');
    Route::post('/add-foods', 'addFoods')->name('addFoods');
    Route::post('/update-coulmn', 'updateCoulmn')->name('updateCoulmn');
    Route::post('/destroy-exercises', 'destroy')->name('destroyExercises');
    Route::post('/check-shape', 'checkShape')->name('checkShape');
    Route::post('/get-activity-customer', 'getActivityCustomer')->name('getActivityCustomer');
  });

  Route::controller(Publications::class)->group(function () {
    Route::get('/publications', 'index')->name('publications');
    Route::post('/add-system', 'addSystem')->name('addSystem');
    Route::post('/update-system', 'updateSystem')->name('updateSystem');
    Route::post('/remove-system', 'removeSystem')->name('removeSystem');
    Route::post('/add-supplement', 'addSupplement')->name('addSupplement');
    Route::post('/add-snack', 'addSnack')->name('addSnack');
    Route::post('/update-supplement', 'updateSupplement')->name('updateSupplement');
    Route::post('/destroy-supplements', 'destroySupplements')->name('destroySupplements');
    Route::get('/notification-discount', 'notificationDiscount')->name('notificationDiscount');
  });

  Route::controller(ImportProduct::class)->group(function () {
    Route::get('/imports', 'index')->name('imports');
    Route::post('/add-product', 'addProduct')->name('addProduct');
    Route::post('/destroy-supplement', 'destroySupplementsAndImports')->name('destroySupplementsAndImports');
  });

  Route::controller(SettingCompanys::class)->group(function () {
    Route::get('/settings-company', 'index')->name('settingsCompany');
    Route::post('/update-employee-profile', 'updateEmployeeProfile')->name('updateEmployeeProfile');
  });

  Route::controller(Coach::class)->group(function () {
    Route::get('/coach-management', 'index')->name('coachManagement');
    Route::post('/coach-management/request-client', 'requestClient')->name('coachManagement.requestClient');
    Route::post('/coach-management/manage', 'manage')->name('coachManagement.manage');
  });

  Route::controller(Customers::class)->group(function () {
    Route::get('/customers', 'index')->name('customers');
    Route::post('/customers/get-all-data-client', 'getAllDataClient')->name('customers.getAllDataClient');
    Route::post('/customers/update-client', 'updateClient')->name('customers.updateClient');
    Route::post('/customers/destroy', 'destroy')->name('customers.destroy');
    Route::post('/customers/regenerate-barcode', 'regenerateBarcode')->name('customers.regenerateBarcode');
    Route::post('/customers/revoke-barcode', 'revokeBarcode')->name('customers.revokeBarcode');
    Route::get('/customers/print-barcode/{code}', 'printBarcode')->name('customers.printBarcode');
  });

  Route::controller(ClientQrScan::class)->group(function () {
    Route::get('/qr-scan', 'index')->name('qrScan');
    Route::post('/qr-scan/scan', 'scan')->name('qrScan.scan');
    Route::post('/qr-scan/record', 'record')->name('qrScan.record');
    Route::post('/qr-scan/record-code', 'recordByCode')->name('qrScan.recordCode');
    Route::post('/qr-scan/record-barcode', 'recordBarcode')->name('qrScan.recordBarcode');
  });

  Route::controller(LogOut::class)->group(function () {
    Route::post('/log-out-employee', 'logOutEmployee')->name('logOutEmployee');
  });
});

// Route::get('/page-mail', function () {
//   return view('Mail.pageMail');
// });

// Route::get('/report', function () {
//   return view('Mail.report');
// });

Route::controller(Records::class)->group(function () {
  Route::match(['get', 'post'], '/auto-record', 'autoRecord')->name('autoRecord');
});
