<?php

require_once 'Model.php';

class Archive extends Model
{
    private $collectionName = 'archives';//tên collection
    //lấy danh sách archive từ hàm chung ở model
    public function getArchiveList(
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
    public function getListIdArchive(
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
    public function getTotalAmountArchive(
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

    public function getAllArchive()
    {
        return $this->find($this->collectionName, []);
    }

    public function getIdArchive()
    {
        return $this->getId($this->collectionName);
    }

    public function createArchive($param = [])
    {
        return $this->create($this->collectionName, $param);
    }

    public function getArchiveById($id)
    {
        return $this->findOne($this->collectionName, ['_id' => $id]);
    }

    public function updateArchive($id, $param = [])
    {
        return $this->updateOne($this->collectionName, $id, $param);
    }

    public function destroy($id)
    {
        return $this->deleteOne($this->collectionName, ['_id' => $id]);
    }
}
