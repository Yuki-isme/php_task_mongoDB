<?php

require_once '../Models/Bill.php';
require_once '../Models/Archive.php';

class BillController extends Controller
{
    // các biến cho model
    private $model;
    private $archive;


    // Khởi tạo cho các biến gọi tới model
    function __construct()
    {
        $this->model = new Bill();
        $this->archive = new Archive();
    }


    // lấy ra danh sach bill và trả về view
    public function index()
    {
        $param = $this->check();
        $page = 1;
        $perPage = 5;
        $search = isset($_SESSION['data']['search']) ? $_SESSION['data']['search'] : '';
        $searchColumn = isset($_SESSION['data']['searchColum']) ? $_SESSION['data']['searchColum'] : [];
        $accountValues = isset($_SESSION['data']['accountValues']) ? $_SESSION['data']['accountValues'] : [];
        $serviceValues = isset($_SESSION['data']['serviceValues']) ? $_SESSION['data']['serviceValues'] : [];
        $statusValues = isset($_SESSION['data']['statusValues']) ? $_SESSION['data']['statusValues'] : [];
        $categoryValues = isset($_SESSION['data']['categoryValues']) ? $_SESSION['data']['categoryValues'] : [];
        $closeDateBegin = isset($_SESSION['data']['closeDateBegin']) ? $_SESSION['data']['closeDateBegin'] : "";
        $closeDateLast = isset($_SESSION['data']['closeDateLast']) ? $_SESSION['data']['closeDateLast'] : "";
        $dateCreatedBegin = isset($_SESSION['data']['dateCreatedBegin']) ? $_SESSION['data']['dateCreatedBegin'] : "";
        $dateCreatedLast = isset($_SESSION['data']['dateCreatedLast']) ? $_SESSION['data']['dateCreatedLast'] : "";
        $dateModifiedBegin = isset($_SESSION['data']['dateModifiedBegin']) ? $_SESSION['data']['dateModifiedBegin'] : "";
        $dateModifiedLast = isset($_SESSION['data']['dateModifiedLast']) ? $_SESSION['data']['dateModifiedLast'] : "";

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            $page = intval($_POST['page']);
            $perPage = intval($_POST['perPage']);
            $search = $_POST['search'];
            $searchColumn = [
                'account' => $_POST['search_account'],
                'bill_id' => $_POST['search_bill_id'],
                'amount' => $_POST['search_amount'],
                'service' => $_POST['search_service'],
                'status' => $_POST['search_status'],
                'category' => $_POST['search_category'],
            ];
            $accountValues = $_POST['accountValues'];
            $serviceValues = $_POST['serviceValues'];
            $statusValues = $_POST['statusValues'];
            $categoryValues = $_POST['categoryValues'];
            $closeDateBegin = $_POST['closeDateBegin'];
            $closeDateLast = $_POST['closeDateLast'];
            $dateCreatedBegin = $_POST['dateCreatedBegin'];
            $dateCreatedLast = $_POST['dateCreatedLast'];
            $dateModifiedBegin = $_POST['dateModifiedBegin'];
            $dateModifiedLast = $_POST['dateModifiedLast'];

            $_SESSION['data'] = [
                'search' => $_POST['search'],
                'searchColum' => [
                    'account' => $_POST['search_account'],
                    'bill_id' => $_POST['search_bill_id'],
                    'amount' => $_POST['search_amount'],
                    'service' => $_POST['search_service'],
                    'status' => $_POST['search_status'],
                    'category' => $_POST['search_category'],
                ],
                'accountValues' => $_POST['accountValues'],
                'serviceValues' => $_POST['serviceValues'],
                'statusValues' => $_POST['statusValues'],
                'categoryValues' => $_POST['categoryValues'],
                'closeDateBegin' => $_POST['closeDateBegin'],
                'closeDateLast' => $_POST['closeDateLast'],
                'dateCreatedBegin' => $_POST['dateCreatedBegin'],
                'dateCreatedLast' => $_POST['dateCreatedLast'],
                'dateModifiedBegin' => $_POST['dateModifiedBegin'],
                'dateModifiedLast' => $_POST['dateModifiedLast'],
            ];
        }

        $bills = $this->model->getBillList(
            $search,
            $page,
            $perPage,
            $param,
            $searchColumn,
            $accountValues,
            $serviceValues,
            $statusValues,
            $categoryValues,
            $closeDateBegin,
            $closeDateLast,
            $dateCreatedBegin,
            $dateCreatedLast,
            $dateModifiedBegin,
            $dateModifiedLast
        );
        $totalPage = $this->model->getTotalPage(
            $search,
            $perPage,
            $param,
            $searchColumn,
            $accountValues,
            $serviceValues,
            $statusValues,
            $categoryValues,
            $closeDateBegin,
            $closeDateLast,
            $dateCreatedBegin,
            $dateCreatedLast,
            $dateModifiedBegin,
            $dateModifiedLast
        );
        $ids = json_encode($this->model->getListIdBill(
            $search,
            $param,
            $searchColumn,
            $accountValues,
            $serviceValues,
            $statusValues,
            $categoryValues,
            $closeDateBegin,
            $closeDateLast,
            $dateCreatedBegin,
            $dateCreatedLast,
            $dateModifiedBegin,
            $dateModifiedLast
        ));
        $total = $this->model->getTotalAmountBill(
            $search,
            $param,
            $searchColumn,
            $accountValues,
            $serviceValues,
            $statusValues,
            $categoryValues,
            $closeDateBegin,
            $closeDateLast,
            $dateCreatedBegin,
            $dateCreatedLast,
            $dateModifiedBegin,
            $dateModifiedLast
        );
        $accounts = $this->model->getAccountList();
        $services = $this->model->getServiceList();
        $categories = $this->model->getCategoryList();
        $statuses = $this->model->getStatusList();

        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {

            $data = [
                'bills' => $bills,
                'page' => $page,
                'totalPage' => $totalPage,
                'total' => $total,
                'accounts' => $accounts,
                'services' => $services,
                'statuses' => $statuses,
                'categories' => $categories,
            ];

            ob_start();
            include('../view/bill/update-view.php');
            $view = ob_get_clean();
            $response = [
                'success' => true,
                'message' => 'Success.',
                'view' => $view,
                'ids' => $ids,
            ];

            echo json_encode($response);
            return;
        }

        return $this->render(
            'index',
            [
                'bills' => $bills,
                'page' => $page, 'perPage' => $perPage,
                'totalPage' => $totalPage,
                'ids' => $ids,
                'total' => $total,
                'accounts' => $accounts,
                'services' => $services,
                'statuses' => $statuses,
                'categories' => $categories
            ]
        );
    }

    // hàm lưu bill, cập nhật table, trả về json
    public function trans()
    {
        $param = $this->check();
        $ids = $_POST['check'];

        foreach ($ids as $id) {
            $bill = $this->model->getBillById(intval($id));

            $this->archive->createArchive([
                '_id' => $this->archive->getIdArchive(),
                'bill_id' => $bill->bill_id,
                'amount' => $bill->amount,
                'account' => $bill->account,
                'service' => $bill->service,
                'category' => $bill->category,
                'status' => $bill->status,
                'user_id' => $bill->user_id,
                'comment' => $bill->comment,
                'close_date' => $bill->close_date,
                'date_created' => $bill->date_created,
                'date_modified' => $bill->date_modified,
            ]);

            $this->model->destroy(intval($id));
        }

        $page = intval($_POST['page']);
        $perPage = intval($_POST['perPage']);
        $search = $_POST['search'];
        $searchColumn = [
            'account' => $_POST['search_account'],
            'bill_id' => $_POST['search_bill_id'],
            'amount' => $_POST['search_amount'],
            'service' => $_POST['search_service'],
            'status' => $_POST['search_status'],
            'category' => $_POST['search_category'],
        ];

        $accountValues = $_POST['accountValues'];
        $serviceValues = $_POST['serviceValues'];
        $statusValues = $_POST['statusValues'];
        $categoryValues = $_POST['categoryValues'];
        $closeDateBegin = $_POST['closeDateBegin'];
        $closeDateLast = $_POST['closeDateLast'];
        $dateCreatedBegin = $_POST['dateCreatedBegin'];
        $dateCreatedLast = $_POST['dateCreatedLast'];
        $dateModifiedBegin = $_POST['dateModifiedBegin'];
        $dateModifiedLast = $_POST['dateModifiedLast'];
        $data = [
            'bills' => $this->model->getBillList(
                $search,
                $page,
                $perPage,
                $param,
                $searchColumn,
                $accountValues,
                $serviceValues,
                $statusValues,
                $categoryValues,
                $closeDateBegin,
                $closeDateLast,
                $dateCreatedBegin,
                $dateCreatedLast,
                $dateModifiedBegin,
                $dateModifiedLast
            ),
            'page' => $page,
            'totalPage' => $this->model->getTotalPage(
                $search,
                $perPage,
                $param,
                $searchColumn,
                $accountValues,
                $serviceValues,
                $statusValues,
                $categoryValues,
                $closeDateBegin,
                $closeDateLast,
                $dateCreatedBegin,
                $dateCreatedLast,
                $dateModifiedBegin,
                $dateModifiedLast
            ),
            'total' => $this->model->getTotalAmountBill(
                $search,
                $param,
                $searchColumn,
                $accountValues,
                $serviceValues,
                $statusValues,
                $categoryValues,
                $closeDateBegin,
                $closeDateLast,
                $dateCreatedBegin,
                $dateCreatedLast,
                $dateModifiedBegin,
                $dateModifiedLast
            ),
            'accounts' => $this->model->getAccountList(),
            'services' => $this->model->getServiceList(),
            'statuses' => $this->model->getStatusList(),
            'categories' => $this->model->getCategoryList(),
        ];
        $ids = json_encode($this->model->getListIdBill(
            $search,
            $param,
            $searchColumn,
            $accountValues,
            $serviceValues,
            $statusValues,
            $categoryValues,
            $closeDateBegin,
            $closeDateLast,
            $dateCreatedBegin,
            $dateCreatedLast,
            $dateModifiedBegin,
            $dateModifiedLast
        ));
        ob_start();
        include('../view/bill/update-view.php');
        $view = ob_get_clean();

        $response = [
            'success' => true,
            'message' => 'Lưu bill thành công.',
            'view' => $view,
            'ids' => $ids,
        ];

        echo json_encode($response);
    }

    //trả về view tạo bill và các select
    public function create()
    {
        $accounts = $this->model->getAccountList();
        $services = $this->model->getServiceList();
        $categories = $this->model->getCategoryList();
        $statuses = $this->model->getStatusList();

        return $this->render(
            'create',
            [
                'accounts' => $accounts,
                'services' => $services,
                'categories' => $categories,
                'statuses' => $statuses
            ]
        );
    }

    // gọi check validate và thực hiện tạo bill, trả về json
    public function store()
    {
        $this->check();
        $bills = $this->model->getAllBill();
        $archives = $this->archive->getAllArchive();
        $accounts = $this->model->getAccountList();
        $services = $this->model->getServiceList();
        $categories = $this->model->getCategoryList();
        $statuses = $this->model->getStatusList();

        $response = [
            'success' => true,
            'message' => '',
        ];

        $response = $this->checkBillId($_POST['bill_id'], $response);
        $response = $this->checkAmount($_POST['amount'], $response);
        $response = $this->checkExist($bills, $archives, $_POST['bill_id'], $response, 0);
        $response = $this->checkForeign($accounts, $services, $categories, $statuses, $_POST['account_id'], $_POST['service_id'], $_POST['category_id'], $_POST['status_id'], $response);
        if (!$response['success']) {
            echo json_encode($response);
            return;
        }

        $this->model->createBill([
            '_id' => $this->model->getIdBill(),
            'bill_id' => $_POST['bill_id'],
            'amount' => intval(ltrim($_POST['amount'])),
            'account' => $this->model->getNameById('accounts', intval($_POST['account_id'])),
            'service' => $this->model->getNameById('services', intval($_POST['service_id'])),
            'category' => $this->model->getNameById('categories', intval($_POST['category_id'])),
            'status' => $this->model->getNameById('statuses', intval($_POST['status_id'])),
            'comment' => $_POST['comment'],
            'user_id' => $_SESSION['user']['_id'],
            'close_date' => $_POST['close_date'],
            'date_created' => $_POST['date_created'],
            'date_modified' => $_POST['date_modified'],
        ]);

        $response['success'] = true;
        $response['message'] = "Tạo bill thành công.";

        echo json_encode($response);
    }

    // trả về form được điền thông tin của bill cần chỉnh sửa và các select
    public function edit($id)
    {
        $this->check();
        $bill = $this->model->getBillById(intval($id));

        $accounts = $this->model->getAccountList();
        $services = $this->model->getServiceList();
        $categories = $this->model->getCategoryList();
        $statuses = $this->model->getStatusList();

        return $this->render(
            'create',
            [
                'bill' => $bill,
                'accounts' => $accounts,
                'services' => $services,
                'categories' => $categories,
                'statuses' => $statuses
            ]
        );
    }

    // thực hiện check validate, exist và cập nhật bill theo id, trả về json
    public function update()
    {
        $bill = $this->model->getBillById(intval($_POST['id']));
        $bills = $this->model->getAllBill();
        $archives = $this->archive->getAllArchive();
        $accounts = $this->model->getAccountList();
        $services = $this->model->getServiceList();
        $categories = $this->model->getCategoryList();
        $statuses = $this->model->getStatusList();

        $response = [
            'success' => true,
            'message' => '',
        ];

        $response = $this->checkBillId($_POST['bill_id'], $response);
        $response = $this->checkAmount($_POST['amount'], $response);
        $response = $this->checkExist($bills, $archives, $_POST['bill_id'], $response, intval($_POST['id']));
        $response = $this->checkForeign(
            $accounts,
            $services,
            $categories,
            $statuses,
            $_POST['account_id'],
            $_POST['service_id'],
            $_POST['category_id'],
            $_POST['status_id'],
            $response
        );
        if (!$response['success']) {
            echo json_encode($response);
            return;
        }

        $param = [
            '$set' => [
                'bill_id' => $_POST['bill_id'],
                'amount' => intval(ltrim($_POST['amount'])),
                'account' => $this->model->getNameById('accounts', intval($_POST['account_id'])),
                'service' => $this->model->getNameById('services', intval($_POST['service_id'])),
                'category' => $this->model->getNameById('categories', intval($_POST['category_id'])),
                'status' => $this->model->getNameById('statuses', intval($_POST['status_id'])),
                'comment' => $_POST['comment'],
                'user_id' => $bill->user_id,
                'close_date' => $_POST['close_date'],
                'date_created' => $_POST['date_created'],
                'date_modified' => $_POST['date_modified'],
            ]
        ];

        $this->model->updateBill(['_id' => intval($_POST['id'])], $param);

        $response['success'] = true;
        $response['message'] = "Cập nhật bill thành công.";

        echo json_encode($response);
    }

    //xóa bill theo id, trả về json
    public function destroy()
    {
        $param = $this->check();

        $this->model->destroy(intval($_POST['id']));

        $page = intval($_POST['page']);
        $perPage = intval($_POST['perPage']);
        $search = $_POST['search'];
        $searchColumn = [
            'account' => $_POST['search_account'],
            'bill_id' => $_POST['search_bill_id'],
            'amount' => $_POST['search_amount'],
            'service' => $_POST['search_service'],
            'status' => $_POST['search_status'],
            'category' => $_POST['search_category'],
        ];

        $accountValues = $_POST['accountValues'];
        $serviceValues = $_POST['serviceValues'];
        $statusValues = $_POST['statusValues'];
        $categoryValues = $_POST['categoryValues'];
        $closeDateBegin = $_POST['closeDateBegin'];
        $closeDateLast = $_POST['closeDateLast'];
        $dateCreatedBegin = $_POST['dateCreatedBegin'];
        $dateCreatedLast = $_POST['dateCreatedLast'];
        $dateModifiedBegin = $_POST['dateModifiedBegin'];
        $dateModifiedLast = $_POST['dateModifiedLast'];
        $data = [
            'bills' => $this->model->getBillList(
                $search,
                $page,
                $perPage,
                $param,
                $searchColumn,
                $accountValues,
                $serviceValues,
                $statusValues,
                $categoryValues,
                $closeDateBegin,
                $closeDateLast,
                $dateCreatedBegin,
                $dateCreatedLast,
                $dateModifiedBegin,
                $dateModifiedLast
            ),
            'page' => $page,
            'totalPage' => $this->model->getTotalPage(
                $search,
                $perPage,
                $param,
                $searchColumn,
                $accountValues,
                $serviceValues,
                $statusValues,
                $categoryValues,
                $closeDateBegin,
                $closeDateLast,
                $dateCreatedBegin,
                $dateCreatedLast,
                $dateModifiedBegin,
                $dateModifiedLast
            ),
            'total' => $this->model->getTotalAmountBill(
                $search,
                $param,
                $searchColumn,
                $accountValues,
                $serviceValues,
                $statusValues,
                $categoryValues,
                $closeDateBegin,
                $closeDateLast,
                $dateCreatedBegin,
                $dateCreatedLast,
                $dateModifiedBegin,
                $dateModifiedLast
            ),
            'accounts' => $this->model->getAccountList(),
            'services' => $this->model->getServiceList(),
            'statuses' => $this->model->getStatusList(),
            'categories' => $this->model->getCategoryList(),
        ];

        ob_start();
        include('../view/bill/update-view.php');
        $view = ob_get_clean();
        $ids = json_encode($this->model->getListIdBill(
            $search,
            $param,
            $searchColumn,
            $accountValues,
            $serviceValues,
            $statusValues,
            $categoryValues,
            $closeDateBegin,
            $closeDateLast,
            $dateCreatedBegin,
            $dateCreatedLast,
            $dateModifiedBegin,
            $dateModifiedLast
        ));
        $response = [
            'success' => true,
            'message' => 'Xóa bill thành công.',
            'view' => $view,
            'ids' => $ids,
        ];

        echo json_encode($response);
    }

    public function quickUpdate()
    {
        $param = [
            '$set' => [
                $_POST['name'] => $_POST['value'],
            ]
        ];

        $this->model->updateBill(['_id' => intval($_POST['id'])], $param);

        $response['success'] = true;
        $response['message'] = "Cập nhật bill thành công.";

        echo json_encode($response);
    }
}
