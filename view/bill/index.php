<div class="col-12">
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="alert alert-success"><?php echo $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']);
    endif; ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title float-start">List Bill</h2>

            <div class="clearfix"></div>


            <div class="float-end" style="margin-left: 10px">
                <a href="/archive/index" class="btn btn-outline-success">
                    View Archived Items
                </a>
            </div>
            <div class="float-end" style="margin-left: 10px">
                <a href="/bill/create" class="btn btn-outline-success">
                    Add New Bill
                </a>
            </div>

            <div class="float-end" style="margin-left: 10px">
                <button class="btn btn-outline-success" id="trans">
                    Archive
                </button>
            </div>

            <div class="float-end">
                <a class="btn btn-outline-danger" href="/auth/logout">
                    Logout
                </a>
            </div>

            <div class="clearfix"></div>

            <div class="d-flex justify-content-between" style="margin-top:10px">
                <div>
                    <input type="text" placeholder="Search" class="form-control rounded" id="search" value="<?= isset($_SESSION['data']['search']) ? $_SESSION['data']['search'] : '' ?>">
                </div>

                <div>
                    <span>Show</span>
                    <input type="number" value="<?= $perPage ?>" class="rounded" style="width: 50px; border-color: #bdbdbd" id="perPage">
                    <span>Items</span>

                </div>
            </div>
        </div>
        <div id="update-view">
            <div class="card-body">
                <table class="table table-hover table-bordered" id="billTable">
                    <thead>
                        <tr>
                            <th scope="col"><input type="checkbox" id="check_all"></th>
                            <th scope="col">
                                <label>Account Number</label>
                                <div class="custom-input">
                                    <input type="text" class="custom-input-search" id="search_account" placeholder="Search Account" value="<?= isset($_SESSION['data']['searchColum']['account']) ? $_SESSION['data']['searchColum']['account'] : '' ?>">
                                    <i class="fa-solid fa-filter filter-icon" id="filter-account"></i>
                                    <div class="filter-check" id="filter-account-check">
                                        <?php
                                        $accountNames = [];
                                        foreach ($accounts as $account) {
                                            $accountNames[] = $account->name;
                                        }
                                        ?>

                                        <ul>
                                            <li>
                                                <input type="checkbox" class="check-all-account" name="all_account" id="all_account" <?= (isset($_SESSION['data']) && is_array($_SESSION['data']['accountValues']) && count($accountNames) == count($_SESSION['data']['accountValues'])) ? 'checked' : '' ?>>
                                                <label style="margin-left: 10px;" for="all_account"> All Account</label>
                                            </li>
                                            <?php foreach ($accountNames as $accountName) : ?>
                                                <li>
                                                    <input type="checkbox" class="account-checkbox" name="account_check[]" value="<?= $accountName ?>" id="account_<?= $accountName ?>" <?= (isset($_SESSION['data']) && is_array($_SESSION['data']['accountValues']) && in_array($accountName, $_SESSION['data']['accountValues'])) ? 'checked' : '' ?>>
                                                    <label style="margin-left: 10px;" for="account_<?= $accountName ?>"><?= $accountName ?></label>
                                                </li>
                                            <?php endforeach; ?>
                                            <li><button id="submit-filter" class="form-control btn btn-outline-primary">Submit</button></li>
                                        </ul>

                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <label>Bill ID</label>
                                <div class="custom-input">
                                    <input type="text" class="custom-input-search" id="search_bill_id" placeholder="Search Bill ID" value="<?= isset($_SESSION['data']['searchColum']['bill_id']) ? $_SESSION['data']['searchColum']['bill_id'] : '' ?>">
                                    <i class="fa-solid fa-filter filter-icon" id="filter-bill-id"></i>
                                    <div class="filter-check" id="filter-bill-id-check">

                                    </div>
                                </div>
                            </th>

                            <th scope="col">
                                <label>Service</label>
                                <div class="custom-input">
                                    <input type="text" class="custom-input-search" id="search_service" placeholder="Search Service" value="<?= isset($_SESSION['data']['searchColum']['service']) ? $_SESSION['data']['searchColum']['service'] : '' ?>">
                                    <i class="fa-solid fa-filter filter-icon" id="filter-service"></i>
                                    <div class="filter-check" id="filter-service-check">
                                        <?php
                                        $serviceNames = [];
                                        foreach ($services as $service) {
                                            $serviceNames[] = $service->name;
                                        }
                                        ?>

                                        <ul>
                                            <li>
                                                <input type="checkbox" class="check-all-service" name="all_service" id="all_service" <?= (isset($_SESSION['data']) && is_array($_SESSION['data']['serviceValues']) && count($serviceNames) == count($_SESSION['data']['serviceValues'])) ? 'checked' : '' ?>>
                                                <label style="margin-left: 10px;" for="all_service"> All Service</label>
                                            </li>
                                            <?php foreach ($serviceNames as $serviceName) : ?>
                                                <li>
                                                    <input type="checkbox" class="service-checkbox" name="service_check[]" value="<?= $serviceName ?>" id="service_<?= $serviceName ?>" <?= (isset($_SESSION['data']) && is_array($_SESSION['data']['serviceValues']) && in_array($serviceName, $_SESSION['data']['serviceValues'])) ? 'checked' : '' ?>>
                                                    <label style="margin-left: 10px;" for="service_<?= $serviceName ?>"><?= $serviceName ?></label>
                                                </li>
                                            <?php endforeach; ?>
                                            <li><button id="submit-filter" class="form-control btn btn-outline-primary">Submit</button></li>
                                        </ul>

                                    </div>
                                </div>
                            </th>

                            <th scope="col">
                                <label>Amount</label>
                                <div class="custom-input">
                                    <input type="text" class="custom-input-search" id="search_amount" placeholder="Search Amount" value="<?= isset($_SESSION['data']['searchColum']['amount']) ? $_SESSION['data']['searchColum']['amount'] : '' ?>">
                                    <i class="fa-solid fa-filter filter-icon" id="filter-amount"></i>
                                    <div class="filter-check" id="filter-amount-check">

                                    </div>
                                </div>
                            </th>

                            <th scope="col">
                                <label>Payment Status</label>
                                <div class="custom-input">
                                    <input type="text" class="custom-input-search" id="search_status" placeholder="Search Status" value="<?= isset($_SESSION['data']['searchColum']['status']) ? $_SESSION['data']['searchColum']['status'] : '' ?>">
                                    <i class="fa-solid fa-filter filter-icon" id="filter-status"></i>
                                    <div class="filter-check" id="filter-status-check">
                                        <?php
                                        $statusNames = [];
                                        foreach ($statuses as $status) {
                                            $statusNames[] = $status->name;
                                        }
                                        ?>

                                        <ul>
                                            <li>
                                                <input type="checkbox" class="check-all-status" name="all_status" id="all_status" <?= (isset($_SESSION['data']) && is_array($_SESSION['data']['statusValues']) && count($statusNames) == count($_SESSION['data']['statusValues'])) ? 'checked' : '' ?>>
                                                <label style="margin-left: 10px;" for="all_status"> All Status</label>
                                            </li>
                                            <?php foreach ($statusNames as $statusName) : ?>
                                                <li>
                                                    <input type="checkbox" class="status-checkbox" name="status_check[]" value="<?= $statusName ?>" id="status_<?= $statusName ?>" <?= (isset($_SESSION['data']) && is_array($_SESSION['data']['statusValues']) && in_array($statusName, $_SESSION['data']['statusValues'])) ? 'checked' : '' ?>>
                                                    <label style="margin-left: 10px;" for="status_<?= $statusName ?>"><?= $statusName ?></label>
                                                </li>
                                            <?php endforeach; ?>
                                            <li><button id="submit-filter" class="form-control btn btn-outline-primary">Submit</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </th>

                            <th scope="col">
                                <label>Category</label>
                                <div class="custom-input">
                                    <input type="text" class="custom-input-search" id="search_category" placeholder="Search Category" value="<?= isset($_SESSION['data']['searchColum']['category']) ? $_SESSION['data']['searchColum']['category'] : '' ?>">
                                    <i class="fa-solid fa-filter filter-icon" id="filter-category"></i>
                                    <div class="filter-check" id="filter-category-check">
                                        <?php
                                        $categoryNames = [];
                                        foreach ($categories as $category) {
                                            $categoryNames[] = $category->name;
                                        }
                                        ?>

                                        <ul>
                                            <li>
                                                <input type="checkbox" class="check-all-category" name="all_category" id="all_category" <?= (isset($_SESSION['data']) && is_array($_SESSION['data']['categoryValues']) && count($categoryNames) == count($_SESSION['data']['categoryValues'])) ? 'checked' : '' ?>>
                                                <label style="margin-left: 10px;" for="all_category"> All Category</label>
                                            </li>
                                            <?php foreach ($categoryNames as $categoryName) : ?>
                                                <li>
                                                    <input type="checkbox" class="category-checkbox" name="category_check[]" value="<?= $categoryName ?>" id="category_<?= $categoryName ?>" <?= (isset($_SESSION['data']) && is_array($_SESSION['data']['categoryValues']) && in_array($categoryName, $_SESSION['data']['categoryValues'])) ? 'checked' : '' ?>>
                                                    <label style="margin-left: 10px;" for="category_<?= $categoryName ?>"><?= $categoryName ?></label>
                                                </li>
                                            <?php endforeach; ?>
                                            <li><button id="submit-filter" class="form-control btn btn-outline-primary">Submit</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </th>
                            <th style="width: 165px;" scope="col">
                                <label>Close Date</label>
                                <div class="custom-input date">
                                    <input type="text" class="custom-input-search" id="search_close_date" placeholder="Date Close">
                                    <i class="fa-solid fa-filter filter-icon" id="filter-close_date"></i>
                                    <div class="filter-check" id="filter-close_date-check">
                                        <ul>
                                            <li style="margin: 5px 0px 5px 0px;">
                                                <input type="checkbox" class="check-all-close_date" name="all_close_date" id="all_close_date" <?= isset($_SESSION['data']['closeDateBegin']) && isset($_SESSION['data']['closeDateLast']) && $_SESSION['data']['closeDateBegin'] != '' && $_SESSION['data']['closeDateLast'] != '' ? '' : 'checked' ?>>
                                                <label style="margin-left: 10px;" for="all_close_date"> All Close Date</label>
                                            </li>
                                            <li style="margin: 5px 0px 5px 0px;">
                                                <label style="margin-left: 10px;"> Close Date Begin</label>
                                                <input style="width: 100%;" type="date" id="close_date_begin" value="<?= isset($_SESSION['data']['closeDateBegin']) ? $_SESSION['data']['closeDateBegin'] : '' ?>">
                                            </li>
                                            <li style="margin: 5px 0px 5px 0px;">
                                                <label style="margin-left: 10px;"> Close Date Last</label>
                                                <input style="width: 100%;" type="date" id="close_date_last" value="<?= isset($_SESSION['data']['closeDateLast']) ? $_SESSION['data']['closeDateLast'] : '' ?>">
                                            </li>
                                            <li><button id="submit-filter" class="form-control btn btn-outline-primary">Submit</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </th>
                            <th style="width: 165px;" scope="col">
                                <label>Date Created</label>
                                <div class="custom-input date">
                                    <input type="text" class="custom-input-search" id="search_date_created" placeholder="Date Close">
                                    <i class="fa-solid fa-filter filter-icon" id="filter-date_created"></i>
                                    <div class="filter-check" id="filter-date_created-check">
                                        <ul>
                                            <li style="margin: 5px 0px 5px 0px;">
                                                <input type="checkbox" class="check-all-date_created" name="all_date_created" id="all_date_created" <?= isset($_SESSION['data']['dateCreatedBegin']) && isset($_SESSION['data']['dateCreatedLast']) && $_SESSION['data']['dateCreatedBegin'] != '' && $_SESSION['data']['dateCreatedLast'] != '' ? '' : 'checked' ?>>
                                                <label style="margin-left: 10px;" for="all_date_created"> All Date Created</label>
                                            </li>
                                            <li style="margin: 5px 0px 5px 0px;">
                                                <label style="margin-left: 10px;"> Date Created Begin</label>
                                                <input style="width: 100%;" type="date" id="date_created_begin" value="<?= isset($_SESSION['data']['dateCreatedBegin']) ? $_SESSION['data']['dateCreatedBegin'] : '' ?>">
                                            </li>
                                            <li style="margin: 5px 0px 5px 0px;">
                                                <label style="margin-left: 10px;"> Date Created Last</label>
                                                <input style="width: 100%;" type="date" id="date_created_last" value="<?= isset($_SESSION['data']['dateCreatedLast']) ? $_SESSION['data']['dateCreatedLast'] : '' ?>">
                                            </li>
                                            <li><button id="submit-filter" class="form-control btn btn-outline-primary">Submit</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </th>
                            <th style="width: 165px;" scope="col">
                                <label>Date Modified</label>
                                <div class="custom-input date">
                                    <input type="text" class="custom-input-search" id="search_date_created" placeholder="Date Close">
                                    <i class="fa-solid fa-filter filter-icon" id="filter-date_modified"></i>
                                    <div class="filter-check" id="filter-date_modified-check">
                                        <ul>
                                            <li style="margin: 5px 0px 5px 0px;">
                                                <input type="checkbox" class="check-all-date_modified" name="all_date_modified" id="all_date_modified" <?= isset($_SESSION['data']['dateModifiedBegin']) && isset($_SESSION['data']['dateModifiedLast']) && $_SESSION['data']['dateModifiedBegin'] == '' && $_SESSION['data']['dateModifiedLast'] == '' ? 'checked' : '' ?>>
                                                <label style="margin-left: 10px;" for="all_date_modified"> All Close Date</label>
                                            </li>
                                            <li style="margin: 5px 0px 5px 0px;">
                                                <label style="margin-left: 10px;"> Close Date Begin</label>
                                                <input style="width: 100%;" type="date" id="date_modified_begin" value="<?= isset($_SESSION['data']['dateModifiedBegin']) ? $_SESSION['data']['dateModifiedBegin'] : '' ?>">
                                            </li>
                                            <li style="margin: 5px 0px 5px 0px;">
                                                <label style="margin-left: 10px;"> Close Date Last</label>
                                                <input style="width: 100%;" type="date" id="date_modified_last" value="<?= isset($_SESSION['data']['dateModifiedLast']) ? $_SESSION['data']['dateModifiedLast'] : '' ?>">
                                            </li>
                                            <li><button id="submit-filter" class="form-control btn btn-outline-primary">Submit</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </th>
                            <th style="width: 70px;" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($bills) : ?>
                            <?php foreach ($bills as $bill) : ?>
                                <tr>
                                    <th scope="row"><input type="checkbox" class="check" value="<?= $bill->_id ?>"></th>
                                    <td>
                                        <select class="form-control" id="update-account" data-id="<?= $bill->_id?>">
                                            <?php foreach ($accountNames as $accountName) : ?>
                                                <option <?= $accountName ==  $bill->account ? 'selected' : '' ?>><?= $accountName ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><?php echo $bill->bill_id ?></td>
                                    <td>
                                        <select class="form-control" id="update-service" data-id="<?= $bill->_id?>">
                                            <?php foreach ($serviceNames as $serviceName) : ?>
                                                <option <?= $serviceName ==  $bill->service ? 'selected' : '' ?>><?= $serviceName ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><?php echo $bill->amount ?></td>
                                    <td>
                                        <select class="form-control" id="update-status" data-id="<?= $bill->_id?>">
                                            <?php foreach ($statusNames as $statusName) : ?>
                                                <option <?= $statusName ==  $bill->status ? 'selected' : '' ?>><?= $statusName ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" id="update-category" data-id="<?= $bill->_id?>">
                                            <?php foreach ($categoryNames as $categoryName) : ?>
                                                <option <?= $categoryName ==  $bill->category ? 'selected' : '' ?>><?= $categoryName ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input class="form-control" type="date" id="update-close_date" data-id="<?= $bill->_id?>" value="<?php echo $bill->close_date ?>"></td>
                                    <td><input class="form-control" type="date" id="update-date_created" data-id="<?= $bill->_id?>" value="<?php echo $bill->date_created ?>"></td>
                                    <td><input class="form-control" type="date" id="update-date_modified" data-id="<?= $bill->_id?>" value="<?php echo $bill->date_modified ?>"></td>
                                    <td>
                                        <a href="/bill/edit/<?= $bill->_id ?>" class="btn btn-outline-primary">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <button data-id="<?= $bill->_id ?>" class="btn btn-outline-danger delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td><?= $total ?></td>
                            <td colspan="5"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer">
                <nav aria-label="Page navigation">
                    <ul class="pagination float-end" id="pagination">
                        <?php if (count($bills) > 0 && $page > 1) : ?>
                            <li class="page-item">
                                <button class="page-link" data-page="<?= $page - 1 ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </button>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                            <li class="page-item <?= $page == $i ? 'active' : '' ?>"><button class="page-link" data-page="<?= $i ?>"><?= $i ?></button></li>
                        <?php endfor; ?>
                        <?php if (count($bills) > 0 && $page < $totalPage) : ?>
                            <li class="page-item">
                                <button class="page-link" data-page="<?= $page + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </button>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>