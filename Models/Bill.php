<?php

require_once 'Model.php';

class Bill extends Model
{
    private $collectionName = 'bills';//tên collection
    //lấy danh sách bill từ hàm chung ở model
    public function getBillList(
        $search,
        $page,
        $perPage,
        $param = [],
        $searchColumn = [],
        $accountValues = [],
        $serviceValues = [],
        $statusValues = [],
        $categoryValues = [],
        $closeDateBegin,
        $closeDateLast,
        $dateCreatedBegin,
        $dateCreatedLast,
        $dateModifiedBegin,
        $dateModifiedLast
    ) {
        return $this->getList(
            $this->collectionName,
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
    }
    //lấy tổng số trang từ hàm chung model
    public function getTotalPage(
        $search,
        $perPage,
        $param = [],
        $searchColumn = [],
        $accountValues = [],
        $serviceValues = [],
        $statusValues = [],
        $categoryValues = [],
        $closeDateBegin,
        $closeDateLast,
        $dateCreatedBegin,
        $dateCreatedLast,
        $dateModifiedBegin,
        $dateModifiedLast
    ) {
        return $this->getTotal(
            $this->collectionName,
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
    }
    //lấy danh sách id từ hàm chung model
    public function getListIdBill(
        $search,
        $param = [],
        $searchColumn = [],
        $accountValues = [],
        $serviceValues = [],
        $statusValues = [],
        $categoryValues = [],
        $closeDateBegin,
        $closeDateLast,
        $dateCreatedBegin,
        $dateCreatedLast,
        $dateModifiedBegin,
        $dateModifiedLast
    ) {
        return $this->getListId(
            $this->collectionName,
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
    }
    //lấy tổng số amout từ hàm chung model
    public function getTotalAmountBill(
        $search,
        $param = [],
        $searchColumn = [],
        $accountValues = [],
        $serviceValues = [],
        $statusValues = [],
        $categoryValues = [],
        $closeDateBegin,
        $closeDateLast,
        $dateCreatedBegin,
        $dateCreatedLast,
        $dateModifiedBegin,
        $dateModifiedLast
    ) {
        return $this->getTotalAmount(
            $this->collectionName,
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
    }

    //các hàm chung từ model

    public function getAllBill()
    {
        return $this->find($this->collectionName, []);
    }

    public function getIdBill()
    {
        return $this->getId($this->collectionName);
    }

    public function createBill($param = [])
    {
        return $this->create($this->collectionName, $param);
    }

    public function getBillById($id)
    {
        return $this->findOne($this->collectionName, ['_id' => $id]);
    }

    public function updateBill($id, $param = [])
    {
        return $this->updateOne($this->collectionName, $id, $param);
    }

    public function destroy($id)
    {
        return $this->deleteOne($this->collectionName, ['_id' => $id]);
    }
}
