<div class="col-12">
    <?php if (isset($_SESSION['error'])) : ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']);
    endif; ?>
    <div style="border: 5px solid #bdbdbd; border-radius: 10px; padding: 20px;">
        <form>
            <div class="row">
                <div class="col-12">
                    <h2>Add New Bill</h2>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Account Number</label>
                        <select class="form-select" name="account_id" id="account_id" required>
                            <?php foreach ($accounts as $account) : ?>
                                <option value="<?= $account->_id ?>" <?= isset($bill) && $bill->account == $account->name ? 'selected' : '' ?>><?= $account->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="error-message-account_id" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Bill ID</label>
                        <input class="form-control" name="bill_id" id="bill_id" type="text" value="<?= isset($bill) ? $bill->bill_id : '' ?>" required>
                        <div id="error-message-bill_id" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Amount</label>
                        <input class="form-control" name="amount" id="amount" type="text" value="<?= isset($bill) ? $bill->amount : '' ?>" required>
                        <div id="error-message-amount" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Service</label>
                        <select class="form-select" name="service_id" id="service_id" required>
                            <?php foreach ($services as $service) : ?>
                                <option value="<?= $service->_id ?>" <?= isset($bill) && $bill->service== $service->name ? 'selected' : '' ?>><?= $service->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="error-message-service_id" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category_id" id="category_id" required>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category->_id ?>" <?= isset($bill) && $bill->category == $category->name ? 'selected' : '' ?>><?= $category->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="error-message-category_id" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Payment Status</label>
                        <select class="form-select" name="status_id" id="status_id" required>
                            <?php foreach ($statuses as $status) : ?>
                                <option value="<?= $status->_id ?>" <?= isset($bill) && $bill->status== $status->name ? 'selected' : '' ?>><?= $status->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="error-message-status_id" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label">Close Date</label>
                        <input class="form-control" type="date" name="close_date" id="close_date" value="<?= isset($bill) ? $bill->close_date : '' ?>">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label">Date Created</label>
                        <input class="form-control" type="date" name="date_created" id="date_created"  value="<?= isset($bill) ? $bill->date_created : date("Y-m-d") ?>">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label">Date Modified</label>
                        <input class="form-control" type="date" name="date_modified" id="date_modified"  value="<?= isset($bill) ? $bill->date_modified : '' ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Comment</label>
                        <textarea class="form-control" name="comment" id="comment"><?= isset($bill) ? $bill->comment : '' ?></textarea>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label"></label>
                        <button type="button" class="btn btn-primary form-control" id="submit-form" data-type="<?= isset($bill) ? 'update' : 'create' ?>" data-id="<?=isset($bill) ? $bill->_id : ''?>" data-model="bill">Save</button>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label"></label>
                        <input type="reset" class="btn btn-danger form-control" value="Reset" id="reset">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label"></label>
                        <a type="button" class="btn btn-secondary form-control" href="/bill/index">Back</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>