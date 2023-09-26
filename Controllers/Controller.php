<?php

class Controller
{
    // hàm thực hiện trả về view
    public function render($view, $params = []) // tên view và các biến gửi kèm
    {
        extract($params);
        $controller = lcfirst(str_replace('Controller', '', get_class($this)));
        ob_start();
        require_once '../view/' . $controller . '/' . $view . '.php';
        $screen = ob_get_clean();
        require_once '../view/layout/layout.php';
    }

    public function check()
    {
        if (!isset($_SESSION['user']['_id'])) {
            return header('Location: /auth/login');
        } else {
            if ($_SESSION['user']['role'] == 'admin') {
                $param = [];
            } else {
                $param = ['user_id' => $_SESSION['user']['_id']];
            }
        }

        return $param;
    }

    // hàm check validate cho billId trả về mảng chứa bool và string
    public function checkBillId($billId, $response)
    {
        if (empty($billId)) {
            $response['success'] = false;
            $response['message'] .= 'Bill ID không được để trống. ';
        }

        if (preg_match('/\s/', $billId)) {
            $response['success'] = false;
            $response['message'] .= 'Bill ID không được chứa khoảng trắng. ';
        }

        if (!ctype_alnum($billId)) {
            $response['success'] = false;
            $response['message'] .= 'Bill ID chỉ được chứa ký tự chữ cái và số. ';
        }

        return $response;
    }

    // hàm check validate cho amount, nhận vào amount được submit trả về mảng chứa bool và string
    public function checkAmount($amount, $response)
    {
        if (empty($amount) || $amount == '') {
            $response['success'] = false;
            $response['message'] .= 'Amount không được để trống. ';
        }

        if (preg_match('/\s/', $amount)) {
            $response['success'] = false;
            $response['message'] .= 'Amount không được chứa khoảng trắng. ';
        }

        if (!ctype_digit($amount)) {
            $response['success'] = false;
            $response['message'] .= 'Amount chỉ được chứa ký tự số. ';
        }

        return $response;
    }

    // check billId đã tồn tại hay chưa
    public function checkExist($bills, $archives, $billId, $response, $_id)
    {
        foreach ($bills as $bill) {
            if ($bill->bill_id == $billId && $bill->_id != $_id) {
                $response['success'] = false;
                $response['message'] .= 'Bill đã tồn tại.';
            }
        }

        foreach ($archives as $archive) {
            if ($archive->bill_id == $_POST['bill_id'] && $archive->_id != $_id) {
                $response['success'] = false;
                $response['message'] .= 'Bill đã tồn tại.';
            }
        }

        return $response;
    }

    //check các id của bảng liên kết có tồn tại không, nhận vào các object, các id được submit => ghép id của object thành mảng và check in_array
    public function checkForeign($accounts, $services, $categories, $statuses, $account_id, $service_id, $category_id, $status_id, $response)
    {
        $accounts_id = [];
        $services_id = [];
        $categories_id = [];
        $statuses_id = [];

        foreach ($accounts as $account) {
            $accounts_id[] = $account->_id;
        }

        foreach ($services as $service) {
            $services_id[] = $service->_id;
        }

        foreach ($categories as $category) {
            $categories_id[] = $category->_id;
        }

        foreach ($statuses as $status) {
            $statuses_id[] = $status->_id;
        }


        if (!in_array($account_id, $accounts_id)) {
            $response['success'] = false;
            $response['message'] .= 'Account không tồn tại.';
        }

        if (!in_array($service_id, $services_id)) {
            $response['success'] = false;
            $response['message'] .= 'Service không tồn tại.';
        }

        if (!in_array($category_id, $categories_id)) {
            $response['success'] = false;
            $response['message'] .= 'Category không tồn tại.';
        }

        if (!in_array($status_id, $statuses_id)) {
            $response['success'] = false;
            $response['message'] .= 'Status không tồn tại.';
        }

        return $response;
    }
}
