<?php

class Model extends Connection
{
    //hàm index chung 
    //lọc dựa trên: ô search chính, param chứa user id, searchColumn: tìm kiếm riên riêngcuar từng cột, các mảng Values là các option selecte, và các khoảng thời gian
    //trả về mảng chứa các điều kiện
    public function index(
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
    ) {
        $regexSearch = new MongoDB\BSON\Regex($search, 'i');// không phân biệt hoa thường
        // Điều kiện lọc cho phần $search
        $searchCondition = [
            '$or' => [
                ['account' => ['$regex' => $regexSearch]],
                ['bill_id' => ['$regex' => $regexSearch]],
                ['service' => ['$regex' => $regexSearch]],
                ['amount' => ['$regex' => $regexSearch]],
                ['amount' => (int) $search],
                ['status' => ['$regex' => $regexSearch]],
                ['category' => ['$regex' => $regexSearch]],
            ],
        ];

        // Điều kiện lọc cho phần $searchColumn
        $columnConditions = [];
        foreach ($searchColumn as $column => $value) {
            if (!empty($value)) {
                $columnConditions[] = [
                    '$or' => [
                        [$column => ['$regex' => new MongoDB\BSON\Regex($value, 'i')]],
                        [$column => (is_numeric($value) ? (int) $value : null)],
                    ],
                ];
            }
        }
        $andConditions = [];
        // Thêm điều kiện cho close_date
        if (!empty($closeDateBegin) || !empty($closeDateLast)) {
            $closeDateCondition = [];
            if (!empty($closeDateBegin)) {
                $closeDateCondition['$gte'] = $closeDateBegin;
            }
            if (!empty($closeDateLast)) {
                $closeDateCondition['$lte'] = $closeDateLast;
            }
            $searchCondition['close_date'] = $closeDateCondition;
        }

        // Thêm điều kiện cho date_created
        if (!empty($dateCreatedBegin) || !empty($dateCreatedLast)) {
            $dateCreatedCondition = [];

            if (!empty($dateCreatedBegin)) {
                $dateCreatedCondition['$gte'] = $dateCreatedBegin;
            }

            if (!empty($dateCreatedLast)) {
                $dateCreatedCondition['$lte'] = $dateCreatedLast;
            }

            $searchCondition['date_created'] = $dateCreatedCondition;
        }

        // Thêm điều kiện cho date_modified
        if (!empty($dateModifiedBegin) || !empty($dateModifiedLast)) {
            $dateModifiedCondition = [];

            if (!empty($dateModifiedBegin)) {
                $dateModifiedCondition['$gte'] = $dateModifiedBegin;
            }

            if (!empty($dateModifiedLast)) {
                $dateModifiedCondition['$lte'] = $dateModifiedLast;
            }

            $searchCondition['date_modified'] = $dateModifiedCondition;
        }

        // Thêm điều kiện cho các giá trị từ Ajax
        $ajaxConditions = [];
        if (!empty($accountValues) || !empty($serviceValues) || !empty($statusValues) || !empty($categoryValues)) {
            $andCondition = ['$and' => []];
            if (!empty($accountValues)) {
                $accountConditions = ['account' => ['$in' => $accountValues]];
                $andCondition['$and'][] = $accountConditions;
            }

            if (!empty($serviceValues)) {
                $serviceConditions = ['service' => ['$in' => $serviceValues]];
                $andCondition['$and'][] = $serviceConditions;
            }
            if (!empty($statusValues)) {
                $statusConditions = ['status' => ['$in' => $statusValues]];
                $andCondition['$and'][] = $statusConditions;
            }
            if (!empty($categoryValues)) {
                $categoryConditions = ['category' => ['$in' => $categoryValues]];
                $andCondition['$and'][] = $categoryConditions;
            }
            $ajaxConditions[] = $andCondition;
        }

        // Nếu có điều kiện từ Ajax, thêm vào mảng $andConditions
        if (!empty($ajaxConditions)) {
            $searchCondition['$and'][] = ['$or' => $ajaxConditions];
        }

        // Thêm điều kiện user_id vào $searchCondition nếu có
        if (!empty($param) && isset($param['user_id'])) {
            $userCondition = ['user_id' => (int) $param['user_id']];
            $searchCondition['$and'][] = $userCondition;
        }

        // Tạo một mảng $and để kết hợp các điều kiện lại với nhau

        if (!empty($searchCondition)) {
            $andConditions[] = $searchCondition;
        }
        if (!empty($columnConditions)) {
            $andConditions = array_merge($andConditions, $columnConditions);
        }

        return $andConditions;
    }
    //hàm lấy danh sách 
    //dựa trên mảng điều kiện của index và page,perPage
    //chuyển đổi trả về kiểu mảng
    public function getList(
        $collectionName,
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
    ) {
        $andConditions = $this->index(
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

        // Thiết lập limit và skip cho phân trang
        $limit = $perPage;
        $skip = ($page - 1) * $perPage;

        // Thực hiện truy vấn MongoDB
        $cursor = $this->getCollection($collectionName)
            ->find(['$and' => $andConditions], ['limit' => $limit, 'skip' => $skip]);

        // Chuyển đổi kết quả thành mảng
        $result = iterator_to_array($cursor);

        return $result;
    }
    //hàm lấy tổng số trang
    //dựa trên mảng điều kiện của index
    //trả về tổng số trang
    public function getTotal(
        $collectionName,
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
    ) {
        $andConditions = $this->index(
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

        $cursor = $this->getCollection($collectionName)
            ->find(['$and' => $andConditions]);

        $result = iterator_to_array($cursor);

        return ceil(count($result) / $perPage);
    }
    //hàm lấy danh sách id
    //dựa trên mảng điều kiện của index
    //trả về mảng chứa id của tất cả bản ghi thỏa mản điều kiện
    public function getListId(
        $collectionName,
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
    ) {
        $andConditions = $this->index(
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

        $cursor = $this->getCollection($collectionName)
            ->find(['$and' => $andConditions]);

        $result = iterator_to_array($cursor);

        $ids = []; // Mảng chứa các giá trị _id

        foreach ($result as $document) {
            if (isset($document->_id)) {
                $ids[] = $document->_id;
            }
        }

        return $ids;
    }
    //hàm lấy tổng amount
    //dựa trên mảng điều kiện của index
    //trả tổng amount của tất cả bản ghi thỏa mãn
    public function getTotalAmount(
        $collectionName,
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
    ) {
        $andConditions = $this->index(
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

        $cursor = $this->getCollection($collectionName)
            ->find(['$and' => $andConditions]);

        $result = iterator_to_array($cursor);

        $amount = 0; // Mảng chứa các giá trị _id

        foreach ($result as $document) {
            if (isset($document->_id)) {
                $amount += $document->amount;
            }
        }

        return $amount;
    }

    //lấy collection
    public function getCollection($collectionName)
    {
        return $this->database->selectCollection($collectionName);
    }
    //tìm bản ghi dựa trên query và where
    public function find($collectionName, $query = [], $where = [])
    {
        return $this->getCollection($collectionName)->find($query, $where);
    }
    //tìm 1 bản ghi dựa trên query và where
    public function findOne($collectionName, $query = [])
    {
        return $this->getCollection($collectionName)->findOne($query);
    }
    //chèn 1 bản ghi dựa trên query
    public function create($collectionName, $param = [])
    {
        return $this->getCollection($collectionName)->insertOne($param);
    }
    //cập nhật 1 bản ghi dựa trên id và query
    public function updateOne($collectionName, $id = [], $query = [])
    {
        return $this->getCollection($collectionName)->updateOne($id, $query);
    }
    //xóa 1 bản ghi dựa trên query(id)
    public function deleteOne($collectionName, $query)
    {
        return $this->getCollection($collectionName)->deleteOne($query);
    }
    //lấy danh sách account
    public function getAccountList()
    {
        return $this->find('accounts', []);
    }
    //lấy danh sách service
    public function getServiceList()
    {
        return $this->find('services', []);
    }
    //lấy danh sách category
    public function getCategoryList()
    {
        return $this->find('categories', []);
    }
    //lấy danh sách status
    public function getStatusList()
    {
        return $this->find('statuses', []);
    }
    //lấy id tăng dần cho mục đích chèn bản ghi kiểu số nguyên
    public function getId($collectionName)
    {
        $maxIdDocument = $this->getCollection($collectionName)->findOne([], ['sort' => ['_id' => -1]]);
        return ($maxIdDocument && isset($maxIdDocument['_id'])) ? $maxIdDocument['_id'] + 1 : 1;
    }
    //lấy name dựa trên id
    public function getNameById($collectionName, $id)
    {
        return $this->getCollection($collectionName)->findOne(['_id' => $id])->name;
    }
}
