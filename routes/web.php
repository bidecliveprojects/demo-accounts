<?php

use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\CashFlowStatementController;
use App\Http\Controllers\ReturnGoodReceiptNoteController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SalesReportController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AcademicDetailController,
    AcademicStatusController,
    AssignmentController,
    LocationController,
    RoasterController,
    CustomAuthController,
    DepartmentController,
    StudentController,
    TeacherController,
    SubjectController,
    SectionController,
    ClassController,
    CountryController,
    StateController,
    CityController,
    ClassTimingController,
    ParaController,
    CompanyController,
    StudentPerformanceController,
    LangController,
    PayPalController,
    EmployeeController,
    ChartOfAccountController,
    PaymentController,
    ReceiptController,
    JournalVoucherController,
    RoleController,
    AttendanceController,
    FeeController,
    HeadController,
    LevelOfPerformanceController,
    SabqiPerformanceController,
    ManzilPerformanceController,
    AdditionalActivityController,
    DeductionTypeController,
    AllowanceTypeController,
    PayrollController,
    ReportController,
    LoanController,
    ParentAuthController,
    ParentController,
    AssignTaskController,
    AssignTestController,
    StudentAttendenceController,
    SettingController,
    AnnouncementController,
    NotificationController,
    CategoryController,
    BrandController,
    SizeController,
    ProductController,
    PurchaseOrderController,
    GoodReceiptNoteController,
    PaymentTypeController,
    SupplierController,
    CustomerController,
    ChartOfAccountSettingController,
    MailController,
    TimeSlotController,
    POSController,
    DirectGoodReceiptNoteController,
    TransferNoteController,
    StockController,
    PurchasePaymentController,
    BankAccountController,
    CashAccountController,
    DirectSaleInvoiceController,
    SaleReceiptController,
    TaxAccountsController,
    PurchaseInvoiceController,
    SaleInvoiceController
};
//use GPBMetadata\Google\Api\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return Redirect::to('dashboard');
    } else {
        return view('auth.login');
    }
    //dd(singlePermission(getSessionCompanyId(), Auth::user()->id, 'right_approve', Auth::user()->acc_type));
});
Route::get('migrate', function () {
    echo Artisan::call('migrate');
    echo 'All migration run successfully';
});
Route::get('/set_user_db_id', [CustomAuthController::class, 'set_user_db_id']);
Route::get('dashboard', [CustomAuthController::class, 'dashboard'])->name('dashboard');
Route::get('/weekly-sales-purchases', [CustomAuthController::class, 'getWeeklySalesAndPurchases'])->name('weekly.sales.purchases');
Route::get('get-top-selling-products', [CustomAuthController::class, 'getTopSellingProducts'])->name('getTopSellingProducts');
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
Route::get('register', [CustomAuthController::class, 'registration'])->name('register');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');
Route::get('send-mail', [MailController::class, 'index']);
Route::get('forgetPasswordForm', [CustomAuthController::class, 'forgetPasswordForm'])->name('forgetPasswordForm');

Route::post('forget_password', [CustomAuthController::class, 'forgetPassword'])->name('forget_password');
Route::get('/resetPasswordForm/{token}', [CustomAuthController::class, 'resetPasswordForm'])->name('resetPasswordForm');
Route::post('reset_password', [CustomAuthController::class, 'resetPassword'])->name('reset_password');

Route::middleware(['auth'])->group(function () {

    Route::get('/change_password', [CustomAuthController::class, 'change_password_form'])->name('change_password');
    Route::post('users/change_password', [CustomAuthController::class, 'changePassword'])->name('changePassword');
    Route::post('users/change_password_two', [CustomAuthController::class, 'changePasswordTwo'])->name('changePasswordTwo');

    Route::post('companies/active/{id}', [CompanyController::class, 'changeInactiveToActiveRecord'])->name('companies.active');
    Route::post('companies/status/{id}', [CompanyController::class, 'status'])->name('companies.status');
    Route::get('companies/addMadrasaAdditionalForm', [CompanyController::class, 'addMadrasaAdditionalForm'])->name('companies.addMadrasaAdditionalForm');
    Route::post('companies/addMadrasaAdditionalDetail', [CompanyController::class, 'addMadrasaAdditionalDetail'])->name('companies.addMadrasaAdditionalDetail');
    Route::get('loadCompanies', [CompanyController::class, 'loadCompanies'])->name('loadCompanies');
    Route::get('loadLocations', [CompanyController::class, 'loadLocations'])->name('loadLocations');
    Route::get('loadSchoolCampusDetailDependCampusIds', [CompanyController::class, 'loadSchoolCampusDetailDependCampusIds'])->name('loadSchoolCampusDetailDependCampusIds');

    Route::resource('companies', CompanyController::class);

    Route::post('locations/active/{id}', [LocationController::class, 'changeInactiveToActiveRecord'])->name('locations.active');
    Route::post('locations/status/{id}', [LocationController::class, 'status'])->name('locations.status');
    Route::resource('locations', LocationController::class);

    Route::post('departments/active/{id}', [DepartmentController::class, 'changeInactiveToActiveRecord'])->name('departments.active');
    Route::post('departments/status/{id}', [DepartmentController::class, 'status'])->name('departments.status');
    Route::resource('departments', DepartmentController::class);

    Route::get('students/montlyPerformanceReport', [StudentController::class, 'montlyPerformanceReport'])->name('montlyPerformanceReport');
    Route::get('students/comletedParasList', [StudentController::class, 'comletedParasList'])->name('comletedParasList');
    Route::get('students/viewParaPerformanceDetail', [StudentController::class, 'viewParaPerformanceDetail'])->name('viewParaPerformanceDetail');
    Route::post('students/active/{id}', [StudentController::class, 'changeInactiveToActiveRecord'])->name('students.active');

    Route::post('students/suspend/{id}', [StudentController::class, 'changeSuspendToUnsuspendedRecord'])->name('students.suspend');
    Route::post('students/unsuspended/{id}', [StudentController::class, 'changeSuspendToUnsuspendedRecord'])->name('students.unsuspended');


    Route::get('students/updateStudentDocumentForm/{id}', [StudentController::class, 'updateStudentDocumentForm'])->name('updateStudentDocumentForm');
    Route::get('students/updateCurrentParaForm', [StudentController::class, 'updateCurrentParaForm'])->name('updateCurrentParaForm');
    Route::post('students/updateCurrentParaDetail', [StudentController::class, 'updateCurrentParaDetail'])->name('updateCurrentParaDetail');
    Route::post('students/updateStudentDocumentDetail/{id}', [StudentController::class, 'updateStudentDocumentDetail'])->name('students.updateStudentDocumentDetail');
    Route::get('students/add_student_activity_performance', [StudentController::class, 'add_student_activity_performance'])->name('add-student-activity-performance');
    Route::post('students/add_student_activity_performance_store', [StudentController::class, 'add_student_activity_performance_store'])->name('add-student-activity-performance-store');
    Route::get('students/attendance-list', [StudentController::class, 'attendance_list'])->name('students.attendance-list');
    Route::get('students/viewStudentAttendanceDetail', [StudentController::class, 'viewStudentAttendanceDetail']);

    Route::resource('students', StudentController::class);

    Route::post('teachers/active/{id}', [TeacherController::class, 'changeInactiveToActiveRecord'])->name('teachers.active');
    Route::resource('teachers', TeacherController::class);

    Route::post('timeslots/active/{id}', [TimeSlotController::class, 'changeInactiveToActiveRecord'])->name('timeslots.active');
    Route::resource('timeslots', TimeSlotController::class);


    Route::post('assignments/active/{id}', [AssignmentController::class, 'changeInactiveToActiveRecord'])->name('assignments.active');
    Route::resource('assignments', AssignmentController::class);
    Route::get('/assignments/{section_id}/subjects', [AssignmentController::class, 'getSubjects']);

    Route::post('subjects/active/{id}', [SubjectController::class, 'changeInactiveToActiveRecord'])->name('subjects.active');
    Route::resource('subjects', SubjectController::class);

    Route::post('roaster/active/{id}', [RoasterController::class, 'changeInactiveToActiveRecord'])->name('roaster.active');
    Route::get('/sections/{section_id}/teacher-subject-assignments', [RoasterController::class, 'getTeacherSubjectAssignmentsForSection']);
    Route::get('/roaster/timeslots/{section_id}', [RoasterController::class, 'getTimeSlots']);
    Route::resource('roaster', RoasterController::class);


    Route::post('sections/active/{id}', [SectionController::class, 'changeInactiveToActiveRecord'])->name('sections.active');
    Route::resource('sections', SectionController::class);

    Route::DELETE('classes/active/{id}', [ClassController::class, 'changeInactiveToActiveRecord'])->name('classes.active');
    Route::get('classes/create-form', [ClassController::class, 'createForm'])->name('classes.createForm');
    Route::resource('classes', ClassController::class);

    Route::post('countries/active/{id}', [CountryController::class, 'changeInactiveToActiveRecord'])->name('countries.active');
    Route::post('countries/status/{id}', [CountryController::class, 'status'])->name('countries.status');
    Route::resource('countries', CountryController::class);

    Route::post('states/active/{id}', [StateController::class, 'changeInactiveToActiveRecord'])->name('states.active');

    Route::post('states/status/{id}', [StateController::class, 'status'])->name('states.status');
    Route::resource('states', StateController::class);

    Route::post('cities/active/{id}', [CityController::class, 'changeInactiveToActiveRecord'])->name('cities.active');
    Route::post('cities/status/{id}', [CityController::class, 'status'])->name('cities.status');
    Route::resource('cities', CityController::class);

    Route::post('classtimings/active/{id}', [ClassTimingController::class, 'changeInactiveToActiveRecord'])->name('classtimings.active');
    Route::resource('classtimings', ClassTimingController::class);

    Route::post('paras/active/{id}', [ParaController::class, 'changeInactiveToActiveRecord'])->name('paras.active');
    Route::get('paras/createParaDetailForm', [ParaController::class, 'createParaDetailForm'])->name('createParaDetailForm');

    Route::post('paras/addOtherParaDetail', [ParaController::class, 'addOtherParaDetail'])->name('addOtherParaDetail');
    Route::get('paras/viewParasOtherDetailList', [ParaController::class, 'viewParasOtherDetailList'])->name('viewParasOtherDetailList');
    Route::resource('paras', ParaController::class);

    Route::get('/send/notification/to/device', [NotificationController::class, 'sendPushNotification'])->name('sendPushNotification');
    Route::get('studentperformances/loadPerformanceDetailAgainstType', [StudentPerformanceController::class, 'loadPerformanceDetailAgainstType'])->name('loadPerformanceDetailAgainstType');
    Route::get('studentperformances/viewStudentPerformanceReport', [StudentPerformanceController::class, 'viewStudentPerformanceReport'])->name('viewStudentPerformanceReport');
    Route::get('studentperformances/viewMonthlyPerformanceReport', [StudentPerformanceController::class, 'viewMonthlyPerformanceReport'])->name('studentperformances.viewMonthlyPerformanceReport');
    Route::get('studentperformances/importStudentPerformance', [StudentPerformanceController::class, 'importStudentPerformance'])->name('studentperformances.importStudentPerformance');
    Route::post('studentperformances/addImportStudentPerformanceDetail', [StudentPerformanceController::class, 'addImportStudentPerformanceDetail'])->name('studentperformances.addImportStudentPerformanceDetail');
    Route::resource('studentperformances', StudentPerformanceController::class);

    Route::post('employees/active/{id}', [EmployeeController::class, 'changeInactiveToActiveRecord'])->name('employees.active');
    //Route::resource('employees',EmployeeController::class);

    Route::controller(EmployeeController::class)->group(function () {
        Route::prefix('employees')->group(function () {
            Route::get('/', 'index')->name('employees.index');
            Route::get('/create', 'create')->name('employees.create');
            Route::post('/store', 'store')->name('employees.store');
            Route::post('/status', 'status')->name('employees.status');
            Route::get('/{id}/edit', 'edit')->name('employees.edit');
            Route::post('/{id}/update', 'update')->name('employees.update');
            Route::post('/destroy/{id}', 'destroy')->name('employees.destroy');
        });
    });


    Route::get('lang/home', [LangController::class, 'index']);
    Route::get('lang/change', [LangController::class, 'change'])->name('changeLang');

    Route::group(['prefix' => 'finance', 'before' => 'csrf'], function () {
        Route::resource('chartofaccounts', ChartOfAccountController::class);
        Route::post('chartofaccounts/active/{id}', [ChartOfAccountController::class, 'changeInactiveToActiveRecord'])->name('chartofaccounts.activeStatus');
        Route::post('chartofaccounts/status/{id}', [ChartOfAccountController::class, 'status'])->name('chartofaccounts.status');

        Route::controller(PurchasePaymentController::class)->group(function () {
            Route::prefix('purchase-payments')->group(function () {
                Route::get('/', 'index')->name('purchase-payments.index');
                Route::get('/create', 'create')->name('purchase-payments.create');
                Route::post('/store', 'store')->name('purchase-payments.store');
                Route::post('/status', 'status')->name('purchase-payments.status');
                Route::get('/{id}/edit', 'edit')->name('purchase-payments.edit');
                Route::post('/{id}/update', 'update')->name('purchase-payments.update');
                Route::post('/destroy/{id}', 'destroy')->name('purchase-payments.destroy');
                Route::get('/show', 'show')->name('purchase-payments.show');
                Route::get('/edit', 'edit')->name('purchase-payments.edit');
            });
        });
        Route::get('/purchase-payments/loadPurchasePaymentVoucherDetailByPONo', [PurchasePaymentController::class, 'loadPurchasePaymentVoucherDetailByPONo']);
        Route::get('/purchase-payments/loadPurchasePaymentVoucherDetailByGRNNo', [PurchasePaymentController::class, 'loadPurchasePaymentVoucherDetailByGRNNo']);
        Route::get('/purchase-payments/loadPurchasePaymentVoucherDetailByInvoiceId', [PurchasePaymentController::class, 'loadPurchasePaymentVoucherDetailByInvoiceId']);
        

        Route::controller(SaleReceiptController::class)->group(function () {
            Route::prefix('sale-receipts')->group(function () {
                Route::get('/', 'index')->name('sale-receipts.index');
                Route::get('/create', 'create')->name('sale-receipts.create');
                Route::post('/store', 'store')->name('sale-receipts.store');
                Route::post('/status', 'status')->name('sale-receipts.status');
                Route::get('/{id}/edit', 'edit')->name('sale-receipts.edit');
                Route::post('/{id}/update', 'update')->name('sale-receipts.update');
                Route::post('/destroy/{id}', 'destroy')->name('sale-receipts.destroy');
                Route::get('/show', 'show')->name('sale-receipts.show');
                Route::get('/edit', 'edit')->name('sale-receipts.edit');
            });
        });
        Route::get('/sale-receipts/loadSaleReceiptVoucherDetailByDSINO', [SaleReceiptController::class, 'loadSaleReceiptVoucherDetailByDSINO']);
        Route::get('/sale-receipts/loadSaleReceiptVoucherDetailByInvoiceId', [SaleReceiptController::class, 'loadSaleReceiptVoucherDetailByInvoiceId']);
        

        Route::controller(PaymentController::class)->group(function () {
            Route::prefix('payments')->group(function () {
                Route::get('/', 'index')->name('payments.index');
                Route::get('/create', 'create')->name('payments.create');
                Route::post('/store', 'store')->name('payments.store');
                Route::post('/status', 'status')->name('payments.status');
                Route::get('/{id}/edit', 'edit')->name('payments.edit');
                Route::post('/{id}/update', 'update')->name('payments.update');
                Route::post('/destroy/{id}', 'destroy')->name('payments.destroy');
                Route::get('/show', 'show')->name('payments.show');
                Route::get('/edit', 'edit')->name('payments.edit');
            });
        });
        Route::post('/payments/reversePaymentVoucher', [PaymentController::class, 'reversePaymentVoucher']);
        Route::post('/payments/approvePaymentVoucher', [PaymentController::class, 'approvePaymentVoucher']);
        Route::post('/payments/deletePaymentVoucher', [PaymentController::class, 'deletePaymentVoucher']);
        Route::post('/payments/paymentVoucherRejectAndRepost', [PaymentController::class, 'paymentVoucherRejectAndRepost']);
        Route::post('/payments/paymentVoucherActiveAndInactive', [PaymentController::class, 'paymentVoucherActiveAndInactive']);

        Route::controller(ReceiptController::class)->group(function () {
            Route::prefix('receipts')->group(function () {
                Route::get('/', 'index')->name('receipts.index');
                Route::get('/create', 'create')->name('receipts.create');
                Route::post('/store', 'store')->name('receipts.store');
                Route::post('/status', 'status')->name('receipts.status');
                Route::get('/{id}/edit', 'edit')->name('payments.edit');
                Route::post('/{id}/update', 'update')->name('receipts.update');
                Route::post('/destroy/{id}', 'destroy')->name('receipts.destroy');
                Route::get('/show', 'show')->name('receipts.show');
                Route::get('/edit', 'edit')->name('receipts.edit');
            });
        });
        Route::post('/receipts/reverseReceiptVoucher', [ReceiptController::class, 'reverseReceiptVoucher']);
        Route::post('/receipts/approveReceiptVoucher', [ReceiptController::class, 'approveReceiptVoucher']);
        Route::post('/receipts/deleteReceiptVoucher', [ReceiptController::class, 'deleteReceiptVoucher']);
        Route::post('/receipts/receiptVoucherRejectAndRepost', [ReceiptController::class, 'receiptVoucherRejectAndRepost']);
        Route::post('/receipts/receiptVoucherActiveAndInactive', [ReceiptController::class, 'receiptVoucherActiveAndInactive']);


        Route::controller(JournalVoucherController::class)->group(function () {
            Route::prefix('journalvouchers')->group(function () {
                Route::get('/', 'index')->name('journalvouchers.index');
                Route::get('/create', 'create')->name('journalvouchers.create');
                Route::post('/store', 'store')->name('journalvouchers.store');
                Route::post('/status', 'status')->name('journalvouchers.status');
                Route::get('/{id}/edit', 'edit')->name('journalvouchers.edit');
                Route::post('/{id}/update', 'update')->name('journalvouchers.update');
                Route::post('/destroy/{id}', 'destroy')->name('journalvouchers.destroy');
                Route::get('/show', 'show')->name('journalvouchers.show');
                Route::get('/edit', 'edit')->name('journalvouchers.edit');
            });
        });
        Route::post('/journalvouchers/reverseJournalVoucher', [JournalVoucherController::class, 'reverseJournalVoucher']);
        Route::post('/journalvouchers/approveJournalVoucher', [JournalVoucherController::class, 'approveJournalVoucher']);
        Route::post('/journalvouchers/deleteJournalVoucher', [JournalVoucherController::class, 'deleteJournalVoucher']);
        Route::post('/journalvouchers/journalVoucherRejectAndRepost', [JournalVoucherController::class, 'journalVoucherRejectAndRepost']);
        Route::post('/journalvouchers/journalVoucherActiveAndInactive', [JournalVoucherController::class, 'journalVoucherActiveAndInactive']);



        //Route::resource('journalvouchers', JournalVoucherController::class);
    });

    Route::controller(PurchaseInvoiceController::class)->group(function () {
        Route::prefix('purchase-invoice')->group(function () {
            Route::get('/', 'index')->name('purchase-invoice.index');
            Route::get('/create', 'create')->name('purchase-invoice.create');
            Route::post('/store', 'store')->name('purchase-invoice.store');
            Route::post('/status', 'status')->name('purchase-invoice.status');
            Route::get('/{id}/edit', 'edit')->name('purchase-invoice.edit');
            Route::post('/update/{id}', 'update')->name('purchase-invoice.update');
            Route::post('/destroy/{id}', 'destroy')->name('purchase-invoice.destroy');
            Route::get('/show', 'show')->name('purchase-invoice.show');
        });
    });

    Route::post('/purchase-invoice/approvePurchaseInvoiceVoucher', [PurchaseInvoiceController::class, 'approvePurchaseInvoiceVoucher']);
    Route::post('/purchase-invoice/reversePurchaseInvoiceVoucher', [PurchaseInvoiceController::class, 'reversePurchaseInvoiceVoucher']);

    Route::controller(SaleInvoiceController::class)->group(function () {
        Route::prefix('sale-invoice')->group(function () {
            Route::get('/', 'index')->name('sale-invoice.index');
            Route::get('/create', 'create')->name('sale-invoice.create');
            Route::post('/store', 'store')->name('sale-invoice.store');
            Route::post('/status', 'status')->name('sale-invoice.status');
            Route::get('/{id}/edit', 'edit')->name('sale-invoice.edit');
            Route::post('/update/{id}', 'update')->name('sale-invoice.update');
            Route::post('/destroy/{id}', 'destroy')->name('sale-invoice.destroy');
            Route::get('/show', 'show')->name('sale-invoice.show');
        });
    });

    Route::post('/sale-invoice/approveSaleInvoiceVoucher', [SaleInvoiceController::class, 'approveSaleInvoiceVoucher']);
    Route::post('/sale-invoice/reverseSaleInvoiceVoucher', [SaleInvoiceController::class, 'reverseSaleInvoiceVoucher']);
    
    Route::controller(RoleController::class)->group(function () {
        Route::prefix('roles')->group(function () {
            Route::get('/', 'index')->name('roles.index');
            Route::get('/create', 'create')->name('roles.create');
            Route::post('/store', 'store')->name('roles.store');
            Route::post('/status', 'status')->name('roles.status');
            Route::get('/{id}/edit', 'edit')->name('roles.edit');
            Route::post('/update/{id}', 'update')->name('roles.update');
            Route::post('/destroy/{id}', 'destroy')->name('roles.destroy');
        });
    });
    Route::controller(AcademicStatusController::class)->group(function () {
        Route::prefix('academic_status')->group(function () {
            Route::get('/', 'index')->name('academic.status.index');
            Route::get('/create', 'create')->name('academic.status.create');
            Route::post('/store', 'store')->name('academic.status.store');
            Route::post('/status', 'status')->name('academic.status.status');
            Route::get('/{id}/edit', 'edit')->name('academic.status.edit');
            Route::put('/update/{id}', 'update')->name('academic.status.update');
            Route::post('/destroy/{id}', 'destroy')->name('academic.status.destroy');
            Route::post('academicstatus/active/{id}', 'changeInactiveToActiveRecord')->name('academic.status.active');
        });
    });
    Route::controller(AcademicDetailController::class)->group(function () {
        Route::prefix('academic_detail')->group(function () {
            Route::get('/', 'index')->name('academic.detail.index');
            Route::get('/create', 'create')->name('academic.detail.create');
            Route::post('/store', 'store')->name('academic.detail.store');
            Route::post('/status', 'status')->name('academic.detail.status');
            Route::get('/{id}/edit', 'edit')->name('academic.detail.edit');
            Route::put('/update/{id}', 'update')->name('academic.detail.update');
            Route::post('/destroy/{id}', 'destroy')->name('academic.detail.destroy');
            Route::post('academicdetail/active/{id}', 'changeInactiveToActiveRecord')->name('academic.detail.active');
        });
    });

    Route::controller(PurchaseOrderController::class)->group(function () {
        Route::prefix('purchase-orders')->group(function () {
            Route::get('/', 'index')->name('purchase-orders.index');
            Route::get('/create', 'create')->name('purchase-orders.create');
            Route::post('/store', 'store')->name('purchase-orders.store');
            Route::post('/status/{id}', 'status')->name('purchase-orders.status');
            Route::get('/{id}/edit', 'edit')->name('purchase-orders.edit');
            Route::post('/update/{id}', 'update')->name('purchase-orders.update');
            Route::get('/show', 'show')->name('payments.show');
            Route::post('/destroy/{id}', 'destroy')->name('purchase-orders.destroy');
        });
    });

    Route::post('/purchase-orders/approvePurchaseOrderVoucher', [PurchaseOrderController::class, 'approvePurchaseOrderVoucher']);
    Route::post('/purchase-orders/purchaseOrderVoucherRejectAndRepost', [PurchaseOrderController::class, 'purchaseOrderVoucherRejectAndRepost']);
    Route::post('/purchase-orders/purchaseOrderVoucherActiveAndInactive', [PurchaseOrderController::class, 'purchaseOrderVoucherActiveAndInactive']);


    Route::controller(TransferNoteController::class)->group(function () {
        Route::prefix('transfer-notes')->group(function () {
            Route::get('/', 'index')->name('transfer-notes.index');
            Route::get('/create', 'create')->name('transfer-notes.create');
            Route::post('/store', 'store')->name('transfer-notes.store');
            Route::post('/status/{id}', 'status')->name('transfer-notes.status');
            Route::get('/{id}/edit', 'edit')->name('transfer-notes.edit');
            Route::post('/update/{id}', 'update')->name('transfer-notes.update');
            Route::post('/destroy/{id}', 'destroy')->name('transfer-notes.destroy');
            Route::get('/show', 'show')->name('transfer-notes.show');
            Route::get('/viewReceiptDetail', 'viewReceiptDetail')->name('transfer-notes.viewReceiptDetail');
            Route::post('/updateTransferNotesReceiptDetail', 'updateTransferNotesReceiptDetail')->name('transfer-notes.updateTransferNotesReceiptDetail');
        });
    });

    Route::post('/transfer-notes/approveTransferNoteVoucher', [TransferNoteController::class, 'approveTransferNoteVoucher']);
    Route::post('/transfer-notes/transferNoteVoucherRejectAndRepost', [TransferNoteController::class, 'transferNoteVoucherRejectAndRepost']);
    Route::post('/transfer-notes/transferNoteVoucherActiveAndInactive', [TransferNoteController::class, 'transferNoteVoucherActiveAndInactive']);


    Route::controller(GoodReceiptNoteController::class)->group(function () {
        Route::prefix('good-receipt-notes')->group(function () {
            Route::get('/', 'index')->name('good-receipt-notes.index');
            Route::get('/create', 'create')->name('good-receipt-notes.create');
            Route::post('/store', 'store')->name('good-receipt-notes.store');
            Route::post('/status/{id}', 'status')->name('good-receipt-notes.status');
            Route::get('/{id}/edit', 'edit')->name('good-receipt-notes.edit');
            Route::post('/update/{goodReceiptNote}', 'update')->name('good-receipt-notes.update');
            Route::post('/destroy/{id}', 'destroy')->name('good-receipt-notes.destroy');
            Route::get('/getPurchaseOrdersBySupplierId', 'getPurchaseOrdersBySupplierId')->name('getPurchaseOrdersBySupplierId');
            Route::get('/getPurchaseOrdersForEdit', 'getPurchaseOrdersForEdit')->name('getPurchaseOrdersForEdit');
            Route::get('/show', 'show')->name('good-receipt-notes.show');
        });
    });


    Route::post('/good-receipt-notes/approveGoodReceiptNoteVoucher', [GoodReceiptNoteController::class, 'approveGoodReceiptNoteVoucher']);
    Route::post('/good-receipt-notes/goodReceiptNoteVoucherRejectAndRepost', [GoodReceiptNoteController::class, 'goodReceiptNoteVoucherRejectAndRepost']);
    Route::post('/good-receipt-notes/goodReceiptNoteVoucherActiveAndInactive', [GoodReceiptNoteController::class, 'goodReceiptNoteVoucherActiveAndInactive']);

    Route::controller(DirectGoodReceiptNoteController::class)->group(function () {
        Route::prefix('direct-good-receipt-note')->group(function () {
            Route::get('/', 'index')->name('direct-good-receipt-note.index');
            Route::get('/create', 'create')->name('direct-good-receipt-note.create');
            Route::post('/store', 'store')->name('direct-good-receipt-note.store');
            Route::post('/status/{id}', 'status')->name('direct-good-receipt-note.status');
            Route::get('/edit/{id}', 'edit')->name('direct-good-receipt-note.edit');
            Route::post('/update/{id}', 'update')->name('direct-good-receipt-note.update');
            Route::post('/destroy/{id}', 'destroy')->name('direct-good-receipt-note.destroy');
            Route::get('/getPurchaseOrdersBySupplierId', 'getPurchaseOrdersBySupplierId')->name('getPurchaseOrdersBySupplierId');
            Route::get('/show', 'show')->name('direct-good-receipt-note.show');
        });
    });

    Route::controller(DirectSaleInvoiceController::class)->group(function () {
        Route::prefix('direct-sale-invoices')->group(function () {
            Route::get('/', 'index')->name('direct-sale-invoices.index');
            Route::get('/create', 'create')->name('direct-sale-invoices.create');
            Route::post('/store', 'store')->name('direct-sale-invoices.store');
            Route::post('/status/{id}', 'status')->name('direct-sale-invoices.status');
            Route::get('/edit/{id}', 'edit')->name('direct-sale-invoices.edit');
            Route::post('/update/{id}', 'update')->name('direct-sale-invoices.update');
            Route::post('/destroy/{id}', 'destroy')->name('direct-sale-invoices.destroy');
            Route::get('/show', 'show')->name('direct-sale-invoices.show');
            Route::get('/getPurchaseOrdersBySupplierId', 'getPurchaseOrdersBySupplierId')->name('getPurchaseOrdersBySupplierId');
            Route::get('/product-wise-average-rate', 'productWiseAverageRate')->name('direct-sale-invoices.product-wise-average-rate');

        });
    });

    Route::post('/direct-sale-invoices/approveDirectSaleInvoiceVoucher', [DirectSaleInvoiceController::class, 'approveDirectSaleInvoiceVoucher']);
    Route::post('/direct-sale-invoices/directSaleInvoiceVoucherRejectAndRepost', [DirectSaleInvoiceController::class, 'directSaleInvoiceVoucherRejectAndRepost']);
    Route::post('/direct-sale-invoices/directSaleInvoiceVoucherActiveAndInactive', [DirectSaleInvoiceController::class, 'directSaleInvoiceVoucherActiveAndInactive']);


    Route::controller(ReturnGoodReceiptNoteController::class)->group(function () {
        Route::prefix('return-good-receipt-notes')->group(function () {
            Route::get('/', 'index')->name('return-good-receipt-notes.index');
            Route::get('/create', 'create')->name('return-good-receipt-notes.create');
            Route::post('/store', 'store')->name('return-good-receipt-notes.store');
            Route::get('load-grn-details',  'loadGRNDetails');
            Route::get('/{id}/edit', 'edit')->name('return-good-receipt-notes.edit');
            Route::post('/update/{goodReceiptNote}', 'update')->name('return-good-receipt-notes.update');
            Route::post('/destroy/{id}', 'destroy')->name('return-good-receipt-notes.destroy');
            Route::post('/status/{id}', 'status')->name('return-good-receipt-notes.status');
            Route::post('/returnGoodReceiptNoteVoucherReject/{id}', 'returnGoodReceiptNoteVoucherReject')->name('return-good-receipt-notes.returnGoodReceiptNoteVoucherReject');
            Route::post('/returnGoodReceiptNoteVoucherRepost/{id}', 'returnGoodReceiptNoteVoucherRepost')->name('return-good-receipt-notes.returnGoodReceiptNoteVoucherRepost');
            Route::post('/approveReturnGoodReceiptNoteVoucher', 'approveReturnGoodReceiptNoteVoucher')->name('return-good-receipt-notes.approveReturnGoodReceiptNoteVoucher');
            Route::get('/getPurchaseOrdersBySupplierId', 'getPurchaseOrdersBySupplierId')->name('getPurchaseOrdersBySupplierId');
            Route::get('/getPurchaseOrdersForEdit', 'getPurchaseOrdersForEdit')->name('getPurchaseOrdersForEdit');
            Route::get('/show', 'show')->name('return-good-receipt-notes.show');
        });
    });

    Route::controller(PaymentTypeController::class)->group(function () {
        Route::prefix('payment-types')->group(function () {
            Route::get('/', 'index')->name('payment-types.index');
            Route::get('/create', 'create')->name('payment-types.create');
            Route::post('/store', 'store')->name('payment-types.store');
            Route::post('/status/{id}', 'status')->name('payment-types.status');
            Route::get('/{id}/edit', 'edit')->name('payment-types.edit');
            Route::post('/update/{id}', 'update')->name('payment-types.update');
            Route::post('/destroy/{id}', 'destroy')->name('payment-types.destroy');
        });
    });

    Route::controller(BankAccountController::class)->group(function () {
        Route::prefix('bank-accounts')->group(function () {
            Route::get('/', 'index')->name('bank-accounts.index');
            Route::get('/create', 'create')->name('bank-accounts.create');
            Route::post('/store', 'store')->name('bank-accounts.store');
            Route::post('/status/{id}', 'status')->name('bank-accounts.status');
            Route::get('/{id}/edit', 'edit')->name('bank-accounts.edit');
            Route::post('/update/{id}', 'update')->name('bank-accounts.update');
            Route::post('/destroy/{id}', 'destroy')->name('bank-accounts.destroy');
        });
    });

    Route::controller(CashAccountController::class)->group(function () {
        Route::prefix('cash-accounts')->group(function () {
            Route::get('/', 'index')->name('cash-accounts.index');
            Route::get('/create', 'create')->name('cash-accounts.create');
            Route::post('/store', 'store')->name('cash-accounts.store');
            Route::post('/status/{id}', 'status')->name('cash-accounts.status');
            Route::get('/{id}/edit', 'edit')->name('cash-accounts.edit');
            Route::post('/update/{id}', 'update')->name('cash-accounts.update');
            Route::post('/destroy/{id}', 'destroy')->name('cash-accounts.destroy');
        });
    });

    Route::controller(SupplierController::class)->group(function () {
        Route::prefix('suppliers')->group(function () {
            Route::get('/', 'index')->name('suppliers.index');
            Route::get('/create', 'create')->name('suppliers.create');
            Route::post('/store', 'store')->name('suppliers.store');
            Route::post('/status/{id}', 'status')->name('suppliers.status');
            Route::get('/{id}/edit', 'edit')->name('suppliers.edit');
            Route::post('/update/{id}', 'update')->name('suppliers.update');
            Route::post('/destroy/{id}', 'destroy')->name('suppliers.destroy');
        });
    });

    Route::controller(CustomerController::class)->group(function () {
        Route::prefix('customers')->group(function () {
            Route::get('/', 'index')->name('customers.index');
            Route::get('/create', 'create')->name('customers.create');
            Route::post('/store', 'store')->name('customers.store');
            Route::post('/status{id}', 'status')->name('customers.status');
            Route::get('/{id}/edit', 'edit')->name('customers.edit');
            Route::post('/update/{id}', 'update')->name('customers.update');
            Route::post('/destroy/{id}', 'destroy')->name('customers.destroy');
        });
    });

    Route::controller(ChartOfAccountSettingController::class)->group(function () {
        Route::prefix('chart-of-account-settings')->group(function () {
            Route::get('/', 'index')->name('chart-of-account-settings.index');
            Route::get('/create', 'create')->name('chart-of-account-settings.create');
            Route::post('/store', 'store')->name('chart-of-account-settings.store');
            Route::post('/active/{id}', 'active')->name('chart-of-account-settings.active');
            Route::get('/{id}/edit', 'edit')->name('chart-of-account-settings.edit');
            Route::post('/update/{id}', 'update')->name('chart-of-account-settings.update');
            Route::post('/destroy/{id}', 'destroy')->name('chart-of-account-settings.destroy');
        });
    });

    Route::controller(StockController::class)->group(function () {
        Route::prefix('stocks')->group(function () {
            Route::get('/openTraceStockModel', 'openTraceStockModel')->name('stocks.openTraceStockModel');
            Route::get('/loadTraceStockDetail', 'loadTraceStockDetail')->name('stocks.loadTraceStockDetail');
        });
    });

    Route::controller(POSController::class)->group(function () {
        Route::prefix('pos')->group(function () {
            Route::get('/', 'index')->name('pos.index');
            Route::get('/create', 'create')->name('pos.create');
            Route::post('/store', 'store')->name('pos.store');
            Route::post('/status', 'status')->name('pos.status');
            Route::get('/{id}/edit', 'edit')->name('pos.edit');
            Route::get('/show', 'show')->name('pos.show');
            Route::post('/update', 'update')->name('pos.update');
            Route::post('/destroy/{id}', 'destroy')->name('pos.destroy');
            Route::post('/filterProducts', 'filterProducts')->name('pos.filterProducts');
            Route::get('/getPurchaseOrdersBySupplierId', 'getPurchaseOrdersBySupplierId')->name('getPurchaseOrdersBySupplierId');
            Route::get('/loadAccountsDependPaymentType', 'loadAccountsDependPaymentType')->name('loadAccountsDependPaymentType');
            Route::get('/today-sales', [POSController::class, 'getTodaySales'])->name('pos.today-sales');
            Route::get('/last-month-sales', [POSController::class, 'getLastMonthSales'])->name('pos.last-month-sales');
        });
    });
    Route::controller(SaleReturnController::class)->group(function () {
        Route::prefix('sales-return')->group(function () {
            Route::get('/', 'index')->name('sales-return.index');
            Route::get('/create', 'create')->name('sales-return.create');
            Route::get('/load-order-details', 'loadOrderDetails')->name('sales-return.loadOrderDetails');
            Route::post('/store', 'store')->name('sales-return.store');
            Route::get('/{id}/edit', 'edit')->name('sales-return.edit');
            Route::get('/show', 'show')->name('sales-return.show');
            Route::post('/update', 'update')->name('sales-return.update');
            Route::post('/destroy/{id}', 'destroy')->name('sales-return.destroy');
            Route::post('/status/{id}', 'status')->name('sales-return.status');
            Route::post('/returnSaleReject/{id}', 'returnSaleReject')->name('sales-return.returnSaleReject');
            Route::post('/returnSaleRepost/{id}', 'returnSaleRepost')->name('sales-return.returnSaleRepost');
            Route::post('/returnSaleApprove/{id}', 'returnSaleApprove')->name('sales-return.returnSaleApprove');
            Route::post('/filterProducts', 'filterProducts')->name('sales-return.filterProducts');
        });
    });

    Route::controller(HeadController::class)->group(function () {
        Route::prefix('heads')->group(function () {
            Route::get('/', 'index')->name('heads.index');
            Route::get('/create', 'create')->name('heads.create');
            Route::post('/store', 'store')->name('heads.store');
            Route::post('/status', 'status')->name('heads.status');
            Route::get('/{id}/edit', 'edit')->name('heads.edit');
            Route::post('/update', 'update')->name('heads.update');
            Route::post('/destroy/{id}', 'destroy')->name('heads.destroy');
        });
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::prefix('categories')->group(function () {
            Route::get('/', 'index')->name('categories.index');
            Route::get('/create', 'create')->name('categories.create');
            Route::post('/store', 'store')->name('categories.store');
            Route::post('/status/{id}', 'status')->name('categories.status');
            Route::get('/{id}/edit', 'edit')->name('categories.edit');
            Route::post('/update/{id}', 'update')->name('categories.update');
            Route::post('/destroy/{id}', 'destroy')->name('categories.destroy');
        });
    });

    Route::controller(TaxAccountsController::class)->group(function () {
        Route::prefix('tax-accounts')->group(function () {
            Route::get('/', 'index')->name('tax-accounts.index');
            Route::get('/create', 'create')->name('tax-accounts.create');
            Route::post('/store', 'store')->name('tax-accounts.store');
            Route::post('/status/{id}', 'status')->name('tax-accounts.status');
            Route::get('/{id}/edit', 'edit')->name('tax-accounts.edit');
            Route::post('/update/{id}', 'update')->name('tax-accounts.update');
            Route::post('/destroy/{id}', 'destroy')->name('tax-accounts.destroy');
        });
    });

    

    Route::controller(BrandController::class)->group(function () {
        Route::prefix('brands')->group(function () {
            Route::get('/', 'index')->name('brands.index');
            Route::get('/create', 'create')->name('brands.create');
            Route::post('/store', 'store')->name('brands.store');
            Route::post('/status/{id}', 'status')->name('brands.status');
            Route::get('/{id}/edit', 'edit')->name('brands.edit');
            Route::post('/update/{id}', 'update')->name('brands.update');
            Route::post('/destroy/{id}', 'destroy')->name('brands.destroy');
        });
    });

    Route::controller(SizeController::class)->group(function () {
        Route::prefix('sizes')->group(function () {
            Route::get('/', 'index')->name('sizes.index');
            Route::get('/create', 'create')->name('sizes.create');
            Route::post('/store', 'store')->name('sizes.store');
            Route::post('/status/{id}', 'status')->name('sizes.status');
            Route::get('/{id}/edit', 'edit')->name('sizes.edit');
            Route::post('/update/{id}', 'update')->name('sizes.update');
            Route::post('/destroy/{id}', 'destroy')->name('sizes.destroy');
        });
    });

    Route::controller(ProductController::class)->group(function () {
        Route::prefix('products')->group(function () {
            Route::get('/', 'index')->name('products.index');
            Route::get('/create', 'create')->name('products.create');
            Route::post('/store', 'store')->name('products.store');
            Route::post('/status/{id}', 'status')->name('products.status');
            Route::get('/{id}/edit', 'edit')->name('products.edit');
            Route::post('/update/{id}', 'update')->name('products.update');
            Route::post('/destroy/{id}', 'destroy')->name('products.destroy');
        });
    });

    Route::controller(AssignTaskController::class)->group(function () {
        Route::prefix('assign-tasks')->group(function () {
            Route::get('/', 'index')->name('assign-tasks.index');
            Route::get('/create', 'create')->name('assign-tasks.create');
            Route::post('/store', 'store')->name('assign-tasks.store');
            Route::post('/status', 'status')->name('assign-tasks.status');
            Route::get('/{id}/edit', 'edit')->name('assign-tasks.edit');
            Route::post('/webupdate', 'webupdate')->name('assign-tasks.webupdate');
            Route::post('/destroy/{id}', 'destroy')->name('assign-tasks.destroy');
            Route::post('/active/{id}', 'changeInactiveToActiveRecord')->name('assign-tasks.active');
            Route::get('/loadSubjectDependTeacherAndSectionIds', 'loadSubjectDependTeacherAndSectionIds')->name('assign-tasks.loadSubjectDependTeacherAndSectionIds');
            Route::get('/student-wise-task-summary-and-performance', 'studentWiseTaskSummaryAndPerformance')->name('assign-tasks.student-wise-task-summary-and-performance');
        });
    });

    Route::controller(SettingController::class)->group(function () {
        Route::prefix('settings')->group(function () {
            Route::get('/', 'index')->name('settings.index');
            Route::get('/create', 'create')->name('settings.create');
            Route::post('/store', 'store')->name('settings.store');
        });
    });

    Route::controller(AssignTestController::class)->group(function () {
        Route::prefix('assign-tests')->group(function () {
            Route::get('/', 'index')->name('assign-tests.index');
            Route::get('/create', 'create')->name('assign-tests.create');
            Route::post('/store', 'store')->name('assign-tests.store');
            Route::post('/status', 'status')->name('assign-tests.status');
            Route::get('/{id}/edit', 'edit')->name('assign-tests.edit');
            Route::post('/update', 'update')->name('assign-tests.update');
            Route::post('/destroy/{id}', 'destroy')->name('assign-tests.destroy');
            Route::get('/loadSubjectDependTeacherAndSectionIds', 'loadSubjectDependTeacherAndSectionIds')->name('assign-tests.loadSubjectDependTeacherAndSectionIds');
            Route::get('/student-wise-test-summary-and-performance', 'studentWiseTestSummaryAndPerformance')->name('assign-tests.student-wise-test-summary-and-performance');
        });
    });

    Route::controller(AnnouncementController::class)->group(function () {
        Route::prefix('announcements')->group(function () {
            Route::get('/', 'index')->name('announcements.index');
            Route::get('/create', 'create')->name('announcements.create');
            Route::post('/store', 'store')->name('announcements.store');
            Route::get('/detail/{id}', 'show')->name('announcements.detail');
        });
    });

    Route::controller(StudentAttendenceController::class)->group(function () {
        Route::prefix('student-attendance')->group(function () {
            Route::get('/', 'index')->name('student-attendance.index');
            Route::get('/create', 'create')->name('student-attendance.create');
            Route::post('/storeMassAttendance', 'storeMassAttendanceTwo')->name('student-attendance.storeMassAttendance');
            Route::get('/loadStudentDependTeacherAndSectionIds', 'loadStudentDependTeacherAndSectionIds')->name('student-attendance.loadStudentDependTeacherAndSectionIds');
        });
    });

    Route::controller(LevelOfPerformanceController::class)->group(function () {
        Route::prefix('levelofperformance')->group(function () {
            Route::get('/', 'index')->name('levelofperformance.index');
            Route::get('/create', 'create')->name('levelofperformance.create');
            Route::post('/store', 'store')->name('levelofperformance.store');
            Route::post('/status', 'status')->name('levelofperformance.status');
            Route::get('/{id}/edit', 'edit')->name('levelofperformance.edit');
            Route::post('/update', 'update')->name('levelofperformance.update');
            Route::post('/destroy/{id}', 'destroy')->name('levelofperformance.destroy');
        });
    });

    Route::controller(SabqiPerformanceController::class)->group(function () {
        Route::prefix('sabqi-performance')->group(function () {
            Route::get('/', 'index')->name('sabqi-performance.index');
            Route::get('/create', 'create')->name('sabqi-performance.create');
            Route::post('/store', 'store')->name('sabqi-performance.store');
            Route::post('/status', 'status')->name('sabqi-performance.status');
            Route::get('/{id}/edit', 'edit')->name('sabqi-performance.edit');
            Route::post('/update', 'update')->name('sabqi-performance.update');
            Route::post('/destroy/{id}', 'destroy')->name('sabqi-performance.destroy');
        });
    });

    Route::controller(ManzilPerformanceController::class)->group(function () {
        Route::prefix('manzil-performance')->group(function () {
            Route::get('/', 'index')->name('manzil-performance.index');
            Route::get('/create', 'create')->name('manzil-performance.create');
            Route::post('/store', 'store')->name('manzil-performance.store');
            Route::post('/status', 'status')->name('manzil-performance.status');
            Route::get('/{id}/edit', 'edit')->name('manzil-performance.edit');
            Route::post('/update', 'update')->name('manzil-performance.update');
            Route::post('/destroy/{id}', 'destroy')->name('manzil-performance.destroy');
        });
    });

    Route::controller(AdditionalActivityController::class)->group(function () {
        Route::prefix('additional-activity')->group(function () {
            Route::get('/', 'index')->name('additional-activity.index');
            Route::get('/create', 'create')->name('additional-activity.create');
            Route::post('/store', 'store')->name('additional-activity.store');
            Route::post('/status', 'status')->name('additional-activity.status');
            Route::get('/{id}/edit', 'edit')->name('additional-activity.edit');
            Route::post('/update', 'update')->name('additional-activity.update');
            Route::post('/destroy/{id}', 'destroy')->name('additional-activity.destroy');
        });
    });

    Route::controller(AttendanceController::class)->group(function () {
        Route::prefix('attendances')->group(function () {
            Route::get('/', 'index')->name('attendances.index');
            Route::get('/import', 'import')->name('attendances.import');
            Route::post('/store', 'store')->name('attendances.store');
            Route::get('/monthlyAttendanceReport', 'monthlyAttendanceReport')->name('attendances.monthlyAttendanceReport');
        });
    });

    Route::controller(FeeController::class)->group(function () {
        Route::prefix('fees')->group(function () {
            Route::get('/', 'index')->name('fees.index');
            Route::get('/create', 'create')->name('fees.create');
            Route::post('/store', 'store')->name('fees.store');
            Route::get('/show', 'show')->name('fees.show');
            Route::get('/viewGenerateFeeVoucherDetail', 'viewGenerateFeeVoucherDetail')->name('fees.viewGenerateFeeVoucherDetail');
            Route::get('/addStudentFeesForm', 'addStudentFeesForm')->name('fees.addStudentFeesForm');
            Route::post('/addStudentFeesDetail', 'addStudentFeesDetail')->name('fees.addStudentFeesDetail');
            Route::get('/generate_fee_voucher', 'generate_fee_voucher')->name('fees.generate-fee-voucher');
            Route::get('/student_wise_fee_voucher_list', 'student_wise_fee_voucher_list')->name('fees.student_wise_fee_voucher_list');

            Route::post('/generate_fee_voucher_store', 'generate_fee_voucher_store')->name('fees.generate-fee-voucher-store');
            Route::get('/generate_fee_voucher_list', 'generate_fee_voucher_list')->name('fees.generate-fee-voucher-list');
            Route::get('/viewGeneratedFeeVouchersMultiple', 'viewGeneratedFeeVouchersMultiple')->name('fees.viewGeneratedFeeVouchersMultiple');

            Route::get('/receipt_voucher_against_fees', 'receipt_voucher_against_fees')->name('fees.receipt-voucher-against-fees');
            Route::post('/receipt_voucher_against_fees_store', 'receipt_voucher_against_fees_store')->name('fees.receipt-voucher-against-fees-store');
        });
    });

    Route::controller(AllowanceTypeController::class)->group(function () {
        Route::prefix('allowance-type')->group(function () {
            Route::get('/', 'index')->name('allowance-type.index');
            Route::get('/create', 'create')->name('allowance-type.create');
            Route::post('/store', 'store')->name('allowance-type.store');
            Route::post('/status', 'status')->name('allowance-type.status');
            Route::get('/{id}/edit', 'edit')->name('allowance-type.edit');
            Route::post('/update/{id}', 'update')->name('allowance-type.update');
            Route::post('/destroy/{id}', 'destroy')->name('allowance-type.destroy');
            Route::post('/active/{id}', 'changeInactiveToActiveRecord')->name('allowance-type.active');
        });
    });

    Route::controller(DeductionTypeController::class)->group(function () {
        Route::prefix('deduction-type')->group(function () {
            Route::get('/', 'index')->name('deduction-type.index');
            Route::get('/create', 'create')->name('deduction-type.create');
            Route::post('/store', 'store')->name('deduction-type.store');
            Route::post('/status', 'status')->name('deduction-type.status');
            Route::get('/{id}/edit', 'edit')->name('deduction-type.edit');
            Route::post('/update/{id}', 'update')->name('deduction-type.update');
            Route::post('/destroy/{id}', 'destroy')->name('deduction-type.destroy');
            Route::post('/active/{id}', 'changeInactiveToActiveRecord')->name('deduction-type.active');
        });
    });

    Route::controller(PayrollController::class)->group(function () {
        Route::prefix('payroll')->group(function () {
            Route::get('/', 'index')->name('payroll.index');
            Route::get('/create', 'create')->name('payroll.create');
            Route::get('/show/{id?}', 'show')->name('payroll.show');
            Route::post('/store', 'store')->name('payroll.store');
            Route::post('/status', 'status')->name('payroll.status');
            Route::get('/{id}/edit', 'edit')->name('payroll.edit');
            Route::post('/update', 'update')->name('payroll.update');
            Route::post('/destroy/{id}', 'destroy')->name('payroll.destroy');
        });
    });

    Route::controller(LoanController::class)->group(function () {
        Route::prefix('loan')->group(function () {
            Route::get('/', 'index')->name('loan.index');
            Route::get('/create', 'create')->name('loan.create');
            Route::post('/store', 'store')->name('loan.store');
            Route::post('/status/{id}', 'status')->name('loan.active');
            Route::get('/{id}/edit', 'edit')->name('loan.edit');
            Route::post('/update/{id}', 'update')->name('loan.update');
            Route::post('/destroy/{id}', 'destroy')->name('loan.destroy');
        });
    });


    Route::get('paypal/pay', [PayPalController::class, 'showPaymentForm']);
    Route::post('paypal/process', [PayPalController::class, 'processPayment']);
    Route::get('paypal/status', [PayPalController::class, 'paymentStatus']);

    Route::controller(ReportController::class)->group(function () {
        Route::prefix('reports')->group(function () {
            Route::get('viewMonthlySummaryReport', 'viewMonthlySummaryReport')->name('reports.viewMonthlySummaryReport');
            Route::get('viewLedgerReport', 'viewLedgerReport')->name('reports.viewLedgerReport');
            Route::get('viewTrialBalanceReport', 'viewTrialBalanceReport')->name('reports.viewTrialBalanceReport');
            Route::get('viewProfitLossReport', 'viewProfitLossReport')->name('reports.viewProfitLossReport');
            Route::get('viewStockReport', 'viewStockReport')->name('reports.viewStockReport');
            Route::get('viewPayableReport', 'viewPayableReport')->name('reports.viewPayableReport');
            Route::get('viewAccountWisePayableSummary', 'viewAccountWisePayableSummary')->name('reports.viewAccountWisePayableSummary');
            Route::get('viewReceivableReport', 'viewReceivableReport')->name('reports.viewReceivableReport');
            Route::get('viewAccountWiseReceivableSummary', 'viewAccountWiseReceivableSummary')->name('reports.viewAccountWiseReceivableSummary');
        });
    });

    Route::get('balance-sheet', [BalanceSheetController::class, 'index'])->name('balance-sheet.index');
    Route::controller(BalanceSheetController::class)->group(function () {
        Route::prefix('balance-sheet-report-settings')->group(function () {
            Route::get('/', 'balanceSheetReportSettingIndex')->name('balance-sheet-report-settings.index');
            Route::get('/create', 'create')->name('balance-sheet-report-settings.create');
            Route::post('/store', 'balanceSheetReportSettingStore')->name('balance-sheet-report-settings.store');
        });
    });


    Route::controller(SettingController::class)->group(function () {
        Route::prefix('profit-and-loss-report-settings')->group(function () {
            Route::get('/', 'profitAndLossReportSettingIndex')->name('profit-and-loss-report-settings.index');
            Route::get('/create', 'profitAndLossReportSettingCreate')->name('profit-and-loss-report-settings.create');
            Route::post('/store', 'profitAndLossReportSettingStore')->name('profit-and-loss-report-settings.store');
        });
    });

    Route::controller(SettingController::class)->group(function () {
        Route::prefix('purchase-invoice-and-payment-setting')->group(function () {
            Route::get('/create', 'purchaseInvoiceAndPaymentSettingCreate')->name('purchase-invoice-and-payment-setting.create');
            Route::post('/store', 'purchaseInvoiceAndPaymentSettingStore')->name('purchase-invoice-and-payment-setting.store');
        });
    });

    Route::controller(SettingController::class)->group(function () {
        Route::prefix('sale-invoice-and-payment-setting')->group(function () {
            Route::get('/create', 'saleInvoiceAndPaymentSettingCreate')->name('sale-invoice-and-payment-setting.create');
            Route::post('/store', 'saleInvoiceAndPaymentSettingStore')->name('sale-invoice-and-payment-setting.store');
        });
    });


    Route::controller(SettingController::class)->group(function () {
        Route::prefix('payable-and-receivable-report-settings')->group(function () {
            Route::get('/', 'payableAndReceivableReportSettingIndex')->name('payable-and-receivable-report-settings.index');
            Route::get('/create', 'payableAndReceivableReportSettingCreate')->name('payable-and-receivable-report-settings.create');
            Route::post('/store', 'payableAndReceivableReportSettingStore')->name('payable-and-receivable-report-settings.store');
        });
    });


    Route::get('cash-flow-statement', [CashFlowStatementController::class, 'index'])->name('reports.cash-flow-statement');
    Route::get('sales-report', [SalesReportController::class, 'index'])->name('reports.viewSalesReport');
});
Route::prefix('parents')->group(function () {
    Route::get('login', [ParentAuthController::class, 'index'])->name('parentLogin');
    Route::post('custom-login', [ParentAuthController::class, 'customLogin'])->name('login.pcustom');
    Route::get('signout', [ParentAuthController::class, 'signOut'])->name('signout');
    Route::middleware(['cnic-validator'])->group(function () {
        Route::get('dashboard', [ParentController::class, 'dashboard'])->name('parents.dashboard');
        Route::get('comletedParasList', [ParentController::class, 'comletedParasList'])->name('parents.comletedParasList');
        Route::get('attendance-list', [ParentController::class, 'attendance_list'])->name('parents.attendance-list');
        Route::get('viewStudentPerformanceReport', [ParentController::class, 'viewStudentPerformanceReport'])->name('parents.viewStudentPerformanceReport');
        Route::get('viewMonthlyPerformanceReport', [ParentController::class, 'viewMonthlyPerformanceReport'])->name('parents.viewMonthlyPerformanceReport');
        Route::get('studentPerformanceList', [ParentController::class, 'studentPerformanceList'])->name('parents.studentPerformanceList');
        Route::get('viewStudentperformancesShow', [ParentController::class, 'viewStudentperformancesShow'])->name('parents.viewStudentperformancesShow');
    });
});





include 'module/users.php';
