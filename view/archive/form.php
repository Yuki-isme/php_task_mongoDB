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
                        <select class="form-select" name="account" id="account_id" required>
                            <?php foreach ($accounts as $account) : ?>
                                <option value="<?= $account->_id ?>" <?= isset($archive) && $archive->account == $account->name ? 'selected' : '' ?>><?= $account->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="error-message-account_id" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Bill ID</label>
                        <input class="form-control" name="bill_id" id="bill_id" type="text" value="<?= isset($archive) ? $archive->bill_id : '' ?>" required>
                        <div id="error-message-bill_id" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Amount</label>
                        <input class="form-control" name="amount" id="amount" type="text" value="<?= isset($archive) ? $archive->amount : '' ?>" required>
                        <div id="error-message-amount" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Service</label>
                        <select class="form-select" name="service" id="service_id" required>
                            <?php foreach ($services as $service) : ?>
                                <option value="<?= $service->_id ?>" <?= isset($archive) && $archive->service == $service->name? 'selected' : '' ?>><?= $service->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="error-message-service_id" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category" id="category_id" required>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category->_id ?>" <?= isset($archive) && $archive->category == $category->name ? 'selected' : '' ?>><?= $category->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="error-message-category_id" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Payment Status</label>
                        <select class="form-select" name="status" id="status_id" required>
                            <?php foreach ($statuses as $status) : ?>
                                <option value="<?= $status->_id ?>" <?= isset($archive) && $archive->status == $status->name? 'selected' : '' ?>><?= $status->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="error-message-status_id" class="error-message text-danger"></div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label">Close Date</label>
                        <input class="form-control" type="date" name="close_date" id="close_date" value="<?= isset($archive) ? $archive->close_date : '' ?>">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label">Date Created</label>
                        <input class="form-control" type="date" name="date_created" id="date_created"  value="<?= isset($archive) ? $archive->date_created : date("Y-m-d") ?>">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label">Date Modified</label>
                        <input class="form-control" type="date" name="date_modified" id="date_modified"  value="<?= isset($archive) ? $archive->date_modified : '' ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Comment</label>
                        <textarea class="form-control" name="comment" id="comment"><?= isset($archive) ? $archive->comment : '' ?></textarea>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-label"></label>
                        <button type="button" class="btn btn-primary form-control" id="submit-form" data-type="update" data-id="<?=$archive->_id?>" data-model="archive">Save</button>
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
                        <a type="button" class="btn btn-secondary form-control" href="/archive/index">Back</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>