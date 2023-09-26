class Index {
  constructor(url_, ids_) {
    this.url_main = url_;
    this.ids = ids_ === null ? [] : JSON.parse(ids_);
    this.dataTableInstance = this.dataTable(); // Lưu đối tượng DataTable vào biến để sử dụng sau này
    this.hasError = false;
    this.initialize();
  }

  initialize() {
    this.login(); // login
    this.transPage();
    this.transPerPage();
    this.search();
    this.searchColumn();
    this.hideShowCheck();
    this.submitCheck();
    this.checkAndTrans(); // check và chuyển bill sang archive và ngược lại
    this.validateOnAll(); // validate cho form
    this.validateAndSubmit(); // validate lần 2 trước khi
    this.confirmDelete(); // alert and confirm delete bill
    this.quickEdit();
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
  //tìm kiếm chung => $or
  search() {
    $(document).on("keyup", "#search", () => {
      this.sendSearchPerPageAjax(1);
    });
  }
  //tìm kiếm riêng => $and
  searchColumn() {
    const self = this; // Lưu tham chiếu của this vào biến self
    $(document).on("keydown", ".custom-input-search", function (event) {
      if (event.which === 13) {
        // Bắt sự kiện khi phím "Enter" được nhấn (mã phím 13)
        self.sendSearchPerPageAjax(1); // Gọi hàm gửi AJAX tại đây
      }
    });

    // $(document).on("blur", ".custom-input-search", function () {
    //   self.sendSearchPerPageAjax(1); // Gọi hàm gửi AJAX khi input mất focus (bỏ chuột ra khỏi input)
    // });
  }
  //ẩn hiện các filter check
  hideShowCheck() {
    $(document).on("click", "#filter-account", function () {
      // Kiểm tra nếu filter-account-check đang ẩn thì hiện lên, ngược lại ẩn đi
      if ($("#filter-account-check").is(":hidden")) {
        $("#filter-account-check").show();
      } else {
        $("#filter-account-check").hide();
      }
    });

    // Sự kiện khi bấm vào filter-service
    $(document).on("click", "#filter-service", function () {
      // Kiểm tra nếu filter-service-check đang ẩn thì hiện lên, ngược lại ẩn đi
      if ($("#filter-service-check").is(":hidden")) {
        $("#filter-service-check").show();
      } else {
        $("#filter-service-check").hide();
      }
    });

    // Sự kiện khi bấm vào filter-status
    $(document).on("click", "#filter-status", function () {
      // Kiểm tra nếu filter-status-check đang ẩn thì hiện lên, ngược lại ẩn đi
      if ($("#filter-status-check").is(":hidden")) {
        $("#filter-status-check").show();
      } else {
        $("#filter-status-check").hide();
      }
    });

    // Sự kiện khi bấm vào filter-category
    $(document).on("click", "#filter-category", function () {
      // Kiểm tra nếu filter-category-check đang ẩn thì hiện lên, ngược lại ẩn đi
      if ($("#filter-category-check").is(":hidden")) {
        $("#filter-category-check").show();
      } else {
        $("#filter-category-check").hide();
      }
    });

    $("#filter-bill-id").click(function () {
      // Kiểm tra nếu filter-account-check đang ẩn thì hiện lên, ngược lại ẩn đi
      if ($("#filter-bill-id-check").is(":hidden")) {
        $("#filter-bill-id-check").show();
      } else {
        $("#filter-bill-id-check").hide();
      }
    });

    $("#filter-amount").click(function () {
      // Kiểm tra nếu filter-account-check đang ẩn thì hiện lên, ngược lại ẩn đi
      if ($("#filter-amount-check").is(":hidden")) {
        $("#filter-amount-check").show();
      } else {
        $("#filter-amount-check").hide();
      }
    });

    $(document).on("click", "#filter-close_date", function () {
      // Kiểm tra nếu filter-account-check đang ẩn thì hiện lên, ngược lại ẩn đi
      if ($("#filter-close_date-check").is(":hidden")) {
        $("#filter-close_date-check").show();
      } else {
        $("#filter-close_date-check").hide();
      }
    });

    $(document).on("click", "#filter-date_created", function () {
      // Kiểm tra nếu filter-account-check đang ẩn thì hiện lên, ngược lại ẩn đi
      if ($("#filter-date_created-check").is(":hidden")) {
        $("#filter-date_created-check").show();
      } else {
        $("#filter-date_created-check").hide();
      }
    });

    $(document).on("click", "#filter-date_modified", function () {
      // Kiểm tra nếu filter-account-check đang ẩn thì hiện lên, ngược lại ẩn đi
      if ($("#filter-date_modified-check").is(":hidden")) {
        $("#filter-date_modified-check").show();
      } else {
        $("#filter-date_modified-check").hide();
      }
    });

    // Sự kiện khi bấm vào Check All Account
    $(document).on("click", ".check-all-account", function () {
      var isChecked = $(this).prop("checked");
      $(".account-checkbox").prop("checked", isChecked);
    });

    // Sự kiện khi bấm vào Check All Service
    $(document).on("click", ".check-all-service", function () {
      var isChecked = $(this).prop("checked");
      $(".service-checkbox").prop("checked", isChecked);
    });

    // Sự kiện khi bấm vào Check All Status
    $(document).on("click", ".check-all-status", function () {
      var isChecked = $(this).prop("checked");
      $(".status-checkbox").prop("checked", isChecked);
    });

    // Sự kiện khi bấm vào Check All Category
    $(document).on("click", ".check-all-category", function () {
      var isChecked = $(this).prop("checked");
      $(".category-checkbox").prop("checked", isChecked);
    });

    // Sự kiện khi bấm vào checkbox con Account
    $(document).on("click", ".account-checkbox", function () {
      var allChecked =
        $(".account-checkbox").length === $(".account-checkbox:checked").length;
      $(".check-all-account").prop("checked", allChecked);
    });

    // Sự kiện khi bấm vào checkbox con Service
    $(document).on("click", ".service-checkbox", function () {
      var allChecked =
        $(".service-checkbox").length === $(".service-checkbox:checked").length;
      $(".check-all-service").prop("checked", allChecked);
    });

    // Sự kiện khi bấm vào checkbox con Status
    $(document).on("click", ".status-checkbox", function () {
      var allChecked =
        $(".status-checkbox").length === $(".status-checkbox:checked").length;
      $(".check-all-status").prop("checked", allChecked);
    });

    // Sự kiện khi bấm vào checkbox con Category
    $(document).on("click", ".category-checkbox", function () {
      var allChecked =
        $(".category-checkbox").length ===
        $(".category-checkbox:checked").length;
      $(".check-all-category").prop("checked", allChecked);
    });

    $(document).on("click", "#all_close_date", function () {
      if ($(this).prop("checked")) {
        $("#close_date_begin").val("");
        $("#close_date_last").val("");
      }
    });

    $(document).on("click", "#all_date_created", function () {
      if ($(this).prop("checked")) {
        $("#date_created_begin").val("");
        $("#date_created_last").val("");
      }
    });

    $(document).on("click", "#all_date_modified", function () {
      if ($(this).prop("checked")) {
        $("#date_modified_begin").val("");
        $("#date_modified_last").val("");
      }
    });

    $(document).on(
      "change",
      "#close_date_begin, #close_date_last",
      function () {
        if (
          $("#close_date_begin").val() == "" &&
          $("#close_date_last").val() == ""
        ) {
          $("#all_close_date").prop("checked", true);
        } else {
          $("#all_close_date").prop("checked", false);
        }
      }
    );

    $(document).on(
      "change",
      "#date_created_begin, #date_created_last",
      function () {
        if (
          $("#date_created_begin").val() == "" &&
          $("#date_created_last").val() == ""
        ) {
          $("#all_date_created").prop("checked", true);
        } else {
          $("#all_date_created").prop("checked", false);
        }
      }
    );

    $(document).on(
      "change",
      "#date_modified_begin, #date_modified_last",
      function () {
        if (
          $("#date_modified_begin").val() == "" &&
          $("#date_modified_last").val() == ""
        ) {
          $("#all_date_modified").prop("checked", true);
        } else {
          $("#all_date_modified").prop("checked", false);
        }
      }
    );
  }
  //submit bộ lọc
  submitCheck() {
    $(document).on("click", "#submit-filter", () => {
      this.sendSearchPerPageAjax(1);
    });
  }
  //lấy dữ liệu dựa trên các ô search, perPage và bọ lọc
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

  //kiểm tra checkbox và lưu/lấy bill, cập nhật lại trang
  checkAndTrans() {
    $(document).on("click", ".check", () => {
      var allChecked =
        $(".check").length === $(".check:checked").length &&
        $(".check").length === this.ids.length; //kiểm tra xem tất cả checkbox con có được check hết không trả về bool
      $("#check_all").prop("checked", allChecked); // check khi checkbox con check hết và bỏ khi ngược lại
    });

    //check và bỏ check checkbox con theo check_all
    $(document).on("click", "#check_all", (event) => {
      var isChecked = $(event.target).prop("checked");
      $(".check").prop("checked", isChecked);
    });

    // lưu/lấy bill, cập nhật lại trang
    $("#trans").on("click", (event) => {
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

      var isChecked = $("#check_all").prop("checked"); // Kiểm tra trạng thái của check_all

      var checkedIds;
      if (isChecked) {
        var checkedIds = this.ids;
      } else {
        var checkedIds = $(".check:checked")
          .map(function () {
            return $(this).val(); //lấy tất cả id của bill được check
          })
          .get();
      }
      this.trans(
        checkedIds,
        1,
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
      ); // gọi hàm ajax lưu/lấy bill, cập nhật trang kèm search, perPage
    });
  }

  // lưu/lấy bill sau đó cập nhật item, phân trang trên trang theo search và tính lại phân trang
  trans(
    checkedIds,
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
      url: this.url_main + "/trans",
      type: "POST",
      data: {
        check: checkedIds,
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
        if (response.success) {
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
            $(".category-checkbox[value='" + value + "']").prop(
              "checked",
              true
            );
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
            $(".status-checkbox").length ===
            $(".status-checkbox:checked").length;
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
          alertify.success(response.message, {
            //thông báo thành công
            cssClass: "ajs-success",
          });
        } else {
          alertify.error(response.message, {
            cssClass: "ajs-error",
          });
        }
      },
      error: () => {
        alertify.error("Có lỗi xảy ra khi gửi yêu cầu.", {
          cssClass: "ajs-error",
        });
      },
    });
  }

  // validate cho tất cả input và select khi có sự kiện
  validateOnAll() {
    $("#bill_id, #amount").on("keyup", () => {
      this.validateAll();
    });

    $("#account_id, #service_id, #category_id, #status_id").on("change", () => {
      this.validateAll();
    });
  }

  // validate cho tất cả input và select khi được gọi
  validateAll() {
    this.validateInput($("#bill_id"));
    this.validateInput($("#amount"));
    this.validateSelect($("#account_id"));
    this.validateSelect($("#service_id"));
    this.validateSelect($("#category_id"));
    this.validateSelect($("#status_id"));
  }

  // hàm check validate của input
  validateInput(input) {
    var value = $(input).val();
    var errorId = "error-message-" + $(input).attr("id");

    if (value.trim() === "") {
      $("#" + errorId).text("Không được bỏ trống.");
      this.hasError = true;
    } else if (
      $(input).attr("id") === "bill_id" &&
      (value.indexOf(" ") !== -1 || !/^[a-zA-Z0-9]+$/.test(value))
    ) {
      $("#" + errorId).text("Chỉ được chứa ký tự chữ cái và số.");
      this.hasError = true;
    } else if (
      $(input).attr("id") === "amount" &&
      (value.indexOf(" ") !== -1 || isNaN(value))
    ) {
      $("#" + errorId).text("Phải là số.");
      this.hasError = true;
    } else {
      $("#" + errorId).empty(); // Xóa thông báo lỗi nếu hợp lệ
    }
  }

  // hàm check validate của select
  validateSelect(select) {
    var value = $(select).val();
    var errorId = "error-message-" + $(select).attr("id");

    if (value === "") {
      $("#" + errorId).text("Vui lòng chọn một tùy chọn.");
      this.hasError = true;
    } else {
      $("#" + errorId).empty(); // Xóa thông báo lỗi nếu hợp lệ
    }
  }

  // check validate lần nữa và submit
  validateAndSubmit() {
    $("#submit-form").on("click", () => {
      $(".error-message").empty();
      this.hasError = false;
      this.validateAll();
      if (!this.hasError) {
        // thực hiệm submit khi không có lỗi validate
        var bill_id = $("#bill_id").val();
        var amount = $("#amount").val();
        var account_id = $("#account_id").val();
        var service_id = $("#service_id").val();
        var category_id = $("#category_id").val();
        var status_id = $("#status_id").val();
        var comment = $("#comment").val();
        var close_date = $("#close_date").val();
        var date_created = $("#date_created").val();
        var date_modified = $("#date_modified").val();
        if ($("#submit-form").data("type") == "update") {
          // kiểm tra xem là form create hay update
          if ($("#submit-form").data("model") == "bill") {
            // kiểm tra xem là update cho bill hay archive
            this.url_main = "/bill";
          } else {
            this.url_main = "/archive";
          }
          this.update(
            $("#submit-form").data("id"), // submit form ajax
            account_id,
            bill_id,
            amount,
            service_id,
            category_id,
            status_id,
            comment,
            close_date,
            date_created,
            date_modified
          );
        } else {
          this.url_main = "/bill";
          this.create(
            // submit form ajax// submit form ajax
            account_id,
            bill_id,
            amount,
            service_id,
            category_id,
            status_id,
            comment,
            close_date,
            date_created,
            date_modified
          );
        }
      }
    });
  }

  //ajax tạo bill và trả kết quả
  create(
    accountId,
    billId,
    amount_,
    serviceId,
    categoryId,
    statusId,
    comment_,
    closeDate,
    dateCreated,
    dateModified
  ) {
    $.ajax({
      url: this.url_main + "/store",
      type: "POST",
      data: {
        account_id: accountId,
        bill_id: billId,
        amount: amount_,
        service_id: serviceId,
        category_id: categoryId,
        status_id: statusId,
        comment: comment_,
        close_date: closeDate,
        date_created: dateCreated,
        date_modified: dateModified,
      },
      dataType: "json",
      success: (response) => {
        if (response.success) {
          $("#reset").click();
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

  // ajax update bill
  update(
    id_,
    accountId,
    billId,
    amount_,
    serviceId,
    categoryId,
    statusId,
    comment_,
    closeDate,
    dateCreated,
    dateModified
  ) {
    $.ajax({
      url: this.url_main + "/update",
      type: "POST",
      data: {
        id: id_,
        account_id: accountId,
        bill_id: billId,
        amount: amount_,
        service_id: serviceId,
        category_id: categoryId,
        status_id: statusId,
        comment: comment_,
        close_date: closeDate,
        date_created: dateCreated,
        date_modified: dateModified,
      },
      dataType: "json",
      success: (response) => {
        if (response.success) {
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

  quickUpdate(dataId, name_, value_) {
    $.ajax({
      url: this.url_main + "/quickUpdate",
      type: "POST",
      data: {
        id: dataId,
        name: name_,
        value: value_,
      },
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alertify.success(response.message, {
            // thông báo thành công
            cssClass: "ajs-success",
          });
        } else {
          alertify.error(response.message, {
            //thông báo lỗi
            cssClass: "ajs-error",
          });
        }
      },
      error: () => {
        alertify.error("Có lỗi xảy ra khi gửi yêu cầu.", {
          //báo lỗi hệ thống
          cssClass: "ajs-error",
        });
      },
    });
  }

  // thông báo xác nhận xóa bill
  confirmDelete() {
    $(document).on("click", ".delete", (event) => {
      var _id = event.currentTarget.getAttribute("data-id");
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

      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          //xác nhận gọi hàm xóa ajax, cập nhật trang kèm search, perPage
          this.delete(
            _id,
            1,
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
      });
    });
  }

  // ajax delete và cập nhật lại trang theo search, tính tại phân trang
  delete(
    dataId,
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
      url: this.url_main + "/destroy",
      type: "POST",
      data: {
        id: dataId,
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
        if (response.success) {
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
            $(".category-checkbox[value='" + value + "']").prop(
              "checked",
              true
            );
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
            $(".status-checkbox").length ===
            $(".status-checkbox:checked").length;
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
          alertify.success(response.message, {
            //thông báo thành công
            cssClass: "ajs-success",
          });
        } else {
          alertify.error(response.message, {
            //thông báo lỗi
            cssClass: "ajs-error",
          });
        }
      },
      error: () => {
        alertify.error("Có lỗi xảy ra khi gửi yêu cầu.", {
          //báo lỗi hệ thống
          cssClass: "ajs-error",
        });
      },
    });
  }

  quickEdit() {
    const self = this;
    $(document).on("change", "#update-account", function () {
      var id = $(this).data("id");
      var name = "account";
      var value = $(this).val();
      self.quickUpdate(id, name, value);
    });

    $(document).on("change", "#update-service", function () {
      var id = $(this).data("id");
      var name = "service";
      var value = $(this).val();
      self.quickUpdate(id, name, value);
    });

    $(document).on("change", "#update-status", function () {
      var id = $(this).data("id");
      var name = "status";
      var value = $(this).val();
      self.quickUpdate(id, name, value);
    });

    $(document).on("change", "#update-category", function () {
      var id = $(this).data("id");
      var name = "category";
      var value = $(this).val();
      self.quickUpdate(id, name, value);
    });

    $(document).on("change", "#update-close_date", function () {
      var id = $(this).data("id");
      var name = "close_date";
      var value = $(this).val();
      self.quickUpdate(id, name, value);
    });

    $(document).on("change", "#update-date_created", function () {
      var id = $(this).data("id");
      var name = "date_created";
      var value = $(this).val();
      self.quickUpdate(id, name, value);
    });

    $(document).on("change", "#update-date_modified", function () {
      var id = $(this).data("id");
      var name = "date_modified";
      var value = $(this).val();
      self.quickUpdate(id, name, value);
    });
  }

  quickUpdate(dataId, name_, value_) {
    $.ajax({
      url: this.url_main + "/quickUpdate",
      type: "POST",
      data: {
        id: dataId,
        name: name_,
        value: value_,
      },
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alertify.success(response.message, {
            // thông báo thành công
            cssClass: "ajs-success",
          });
        } else {
          alertify.error(response.message, {
            //thông báo lỗi
            cssClass: "ajs-error",
          });
        }
      },
      error: () => {
        alertify.error("Có lỗi xảy ra khi gửi yêu cầu.", {
          //báo lỗi hệ thống
          cssClass: "ajs-error",
        });
      },
    });
  }
}
