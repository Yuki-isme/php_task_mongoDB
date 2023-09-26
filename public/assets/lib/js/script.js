class Index {
  constructor(url_, ids_) {
    this.url_main = url_;
    this.ids = ids_ === null ? [] : JSON.parse(ids_);
    this.dataTableInstance = this.dataTable(); // Lưu đối tượng DataTable vào biến để sử dụng sau này
    this.hasError = false;
    this.initialize();
  }

  initialize() {
    this.searchColumn();
    this.transPage();
    this.transPerPage();
    this.login(); // login
  }

  dataTable() {
    return $("#billTable").DataTable({
      paging: false,
      searching: false,
      language: {
        info: "",
      },
      order: [], // Bỏ sắp xếp theo cột đầu tiên
      columnDefs: [
        { orderable: false, targets: 0 }, // Tắt sắp xếp cho cột đầu tiên (checkbox)
      ],
    });
  }

  //get thông tin đăng nhập
  login() {
    $("#login").on("click", () => {
      var _username = $("#username").val();
      var _password = $("#password").val();
      this.auth(_username, _password);
    });
  }

  //gửi thông tin đăng nhập thông báo lỗi hoặc chuyển hướng
  auth(_username, _password) {
    $.ajax({
      url: "/auth/auth",
      type: "POST",
      data: {
        username: _username,
        password: _password,
      },
      dataType: "json",
      success: (response) => {
        if (response.success) {
          window.location.href = "/bill/index";
          alertify.success(response.message, {
            //thông báo thành công
            cssClass: "ajs-success",
          });
        } else {
          alertify.error(response.message, {
            // thông báo lỗi validate
            cssClass: "ajs-error",
          });
        }
      },
      error: () => {
        alertify.error("Có lỗi xảy ra khi gửi yêu cầu.", {
          // lỗi hệ thống
          cssClass: "ajs-error",
        });
      },
    });
  }

  transPage() {
    const self = this; // Lưu lại ngữ cảnh của đối tượng
    $(document).on("click", ".page-link", function () {
      self.sendSearchPerPageAjax($(this).data("page"));
    });
  }

  transPerPage() {
    $(document).on("change", "#perPage", () => {
      this.sendSearchPerPageAjax(1);
    });
  }

  sendSearchPerPageAjax(perPage_) {
    var search_account_ = $("#search_account").val();
    var search_bill_id_ = $("#search_bill_id").val();
    var search_service_ = $("#search_service").val();
    var search_amount_ = $("#search_amount").val();
    var search_status_ = $("#search_status").val();
    var search_category_ = $("#search_category").val();

    var accountValues_ = [];
    $(".account-checkbox:checked").each(function () {
      accountValues_.push($(this).val());
    });

    // Lấy dữ liệu từ phần Service
    var serviceValues_ = [];
    $(".service-checkbox:checked").each(function () {
      serviceValues_.push($(this).val());
    });

    // Lấy dữ liệu từ phần Status
    var statusValues_ = [];
    $(".status-checkbox:checked").each(function () {
      statusValues_.push($(this).val());
    });

    // Lấy dữ liệu từ phần Category
    var categoryValues_ = [];
    $(".category-checkbox:checked").each(function () {
      categoryValues_.push($(this).val());
    });

    var close_date_begin_ = $("#close_date_begin").val();
    var close_date_last_ = $("#close_date_last").val();

    var date_created_begin_ = $("#date_created_begin").val();
    var date_created_last_ = $("#date_created_last").val();

    var date_modified_begin_ = $("#date_modified_begin").val();
    var date_modified_last_ = $("#date_modified_last").val();

    this.index(
      perPage_,
      $("#perPage").val(),
      $("#search").val(),
      search_account_,
      search_bill_id_,
      search_service_,
      search_amount_,
      search_status_,
      search_category_,
      accountValues_,
      serviceValues_,
      statusValues_,
      categoryValues_,
      close_date_begin_,
      close_date_last_,
      date_created_begin_,
      date_created_last_,
      date_modified_begin_,
      date_modified_last_
    );
  }

  index(
    page_,
    perPage_,
    search_,
    search_account_,
    search_bill_id_,
    search_service_,
    search_amount_,
    search_status_,
    search_category_,
    accountValues_,
    serviceValues_,
    statusValues_,
    categoryValues_,
    close_date_begin_,
    close_date_last_,
    date_created_begin_,
    date_created_last_,
    date_modified_begin_,
    date_modified_last_
  ) {
    $.ajax({
      url: this.url_main + "/index",
      type: "POST",
      data: {
        page: page_,
        perPage: perPage_,
        search: search_,
        search_account: search_account_,
        search_bill_id: search_bill_id_,
        search_service: search_service_,
        search_amount: search_amount_,
        search_status: search_status_,
        search_category: search_category_,
        accountValues: accountValues_.length > 0 ? accountValues_ : "",
        serviceValues: serviceValues_.length > 0 ? serviceValues_ : "",
        statusValues: statusValues_.length > 0 ? statusValues_ : "",
        categoryValues: categoryValues_.length > 0 ? categoryValues_ : "",
        closeDateBegin: close_date_begin_,
        closeDateLast: close_date_last_,
        dateCreatedBegin: date_created_begin_,
        dateCreatedLast: date_created_last_,
        dateModifiedBegin: date_modified_begin_,
        dateModifiedLast: date_modified_last_,
      },
      dataType: "json",
      success: (response) => {
        // Xóa hủy DataTable cũ
        this.dataTableInstance.destroy();
        $("#update-view").html(response.view);
        this.dataTableInstance = this.dataTable();
        this.ids = JSON.parse(response.ids);

        $("#search_account").val(search_account_);
        $("#search_bill_id").val(search_bill_id_);
        $("#search_amount").val(search_amount_);
        $("#search_service").val(search_service_);
        $("#search_status").val(search_status_);
        $("#search_category").val(search_category_);

        $.each(accountValues_, function (index, value) {
          $(".account-checkbox[value='" + value + "']").prop("checked", true);
        });
        $.each(serviceValues_, function (index, value) {
          $(".service-checkbox[value='" + value + "']").prop("checked", true);
        });
        $.each(statusValues_, function (index, value) {
          $(".status-checkbox[value='" + value + "']").prop("checked", true);
        });
        $.each(categoryValues_, function (index, value) {
          $(".category-checkbox[value='" + value + "']").prop("checked", true);
        });
        var allAccountChecked =
          $(".account-checkbox").length ===
          $(".account-checkbox:checked").length;
        $(".check-all-account").prop("checked", allAccountChecked);
        var allServiceChecked =
          $(".service-checkbox").length ===
          $(".service-checkbox:checked").length;
        $(".check-all-service").prop("checked", allServiceChecked);
        var allStatusChecked =
          $(".status-checkbox").length === $(".status-checkbox:checked").length;
        $(".check-all-status").prop("checked", allStatusChecked);
        var allCategoryChecked =
          $(".category-checkbox").length ===
          $(".category-checkbox:checked").length;
        $(".check-all-category").prop("checked", allCategoryChecked);

        $("#close_date_begin").val(close_date_begin_);
        $("#close_date_last").val(close_date_last_);

        $("#date_created_begin").val(date_created_begin_);
        $("#date_created_last").val(date_created_last_);

        $("#date_modified_begin").val(date_modified_begin_);
        $("#date_modified_last").val(date_created_last_);
      },
      error: (xhr, status, error) => {},
    });
  }
}
