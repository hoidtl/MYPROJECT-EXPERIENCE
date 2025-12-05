<!-- THÔNG BÁO -->
<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form 
    action="<?php echo isset($data['editItem']) 
        ? APP_URL . '/Product/edit/' . $data['editItem']['masp'] 
        : APP_URL . '/Product/create'; ?>" 
    method="post" 
    enctype="multipart/form-data"
    class="container mt-4"
>
    <div class="card shadow">

        <!-- TITLE -->
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <?php echo isset($data['editItem']) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới'; ?>
            </h5>
        </div>

        <div class="card-body row g-3">

            <!-- HÌNH -->
            <div class="col-md-6">
                <?php 
                if (isset($data['editItem']) && $data['editItem']['hinhanh']) {
                    echo "<img src='" . APP_URL . "/public/images/" . $data['editItem']['hinhanh'] . "' class='img-thumbnail mb-2' style='height: 10rem;'>";
                } else { ?>
                    <img src="<?php echo APP_URL ?>/public/images/defaut.png" class="img-thumbnail mb-2" style="height: 10rem;">
                <?php } ?>
            </div>

            <!-- MÃ LOẠI & MÃ SP -->
            <div class="col-md-6">
                <label class="form-label">Loại sản phẩm</label>
                <select name="txt_maloaisp" class="form-select" required>
                    <option value="">-- Chọn loại sản phẩm --</option>
                    <?php
                    foreach ($data["producttype"] as $v) {
                        $sel = isset($data['editItem']) && $data['editItem']['tenLoaiSP'] == $v["tenLoaiSP"] ? "selected" : "";
                        echo "<option value='{$v["tenLoaiSP"]}' $sel>{$v["tenLoaiSP"]}</option>";
                    }
                    ?>
                </select>

                <br>

                <label class="form-label">Mã sản phẩm</label>
                <input type="text" name="txt_masp" class="form-control"
                    value="<?php echo isset($data['editItem']) ? $data['editItem']['masp'] : ''; ?>"
                    <?php echo isset($data['editItem']) ? 'readonly' : ''; ?> required>
            </div>

            <!-- UPLOAD ẢNH -->
            <div class="col-md-6">
                <label class="form-label">Hình ảnh</label>
                <input type="file" name="uploadfile" class="form-control" accept="image/*">
            </div>

            <!-- TÊN -->
            <div class="col-md-6">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="txt_tensp" class="form-control"
                    value="<?php echo isset($data['editItem']) ? $data['editItem']['tensp'] : ''; ?>" required>
            </div>

            <!-- SỐ LƯỢNG -->
            <div class="col-md-6">
                <label class="form-label">Số lượng</label>
                <input type="number" name="txt_soluong" class="form-control" min="0"
                    value="<?php echo isset($data['editItem']) ? $data['editItem']['soluong'] : '0'; ?>" required>
            </div>

            <!-- MÔ TẢ -->
            <div class="col-md-12">
                <label class="form-label">Mô tả</label>
                <textarea name="txt_mota" rows="3" class="form-control"><?php 
                    echo isset($data['editItem']) ? $data['editItem']['mota'] : ''; 
                ?></textarea>
            </div>

        </div>

        <!-- SIZE -->
<div class="px-4 pb-3">
    <label class="form-label fw-bold">Kích thước & Giá theo size</label>

    <div id="size-container">
        <?php 
        if (!empty($data['sizes'])) {
            foreach ($data['sizes'] as $s) {
                echo "
                <div class='row g-2 mb-2 size-row'>
                    <div class='col-md-3'>
                        <input type='text' name='size[]' class='form-control' value='{$s['size']}' required>
                    </div>
                    <div class='col-md-3'>
                        <input type='number' name='giaNhap[]' class='form-control' value='{$s['giaNhap']}' min='0' required placeholder='Giá nhập'>
                    </div>
                    <div class='col-md-3'>
                        <input type='number' name='giaXuat[]' class='form-control' value='{$s['giaXuat']}' min='0' required placeholder='Giá xuất'>
                    </div>
                    <div class='col-md-3'>
                        <button type='button' class='btn btn-danger btn-remove-size w-100'>X</button>
                    </div>
                </div>";
            }
        } else {
            echo "
            <div class='row g-2 mb-2 size-row'>
                <div class='col-md-3'>
                    <input type='text' name='size[]' class='form-control' placeholder='Size (S,M,L...)' required>
                </div>
                <div class='col-md-3'>
                    <input type='number' name='giaNhap[]' class='form-control' placeholder='Giá nhập' min='0' required>
                </div>
                <div class='col-md-3'>
                    <input type='number' name='giaXuat[]' class='form-control' placeholder='Giá xuất' min='0' required>
                </div>
                <div class='col-md-3'>
                    <button type='button' class='btn btn-danger btn-remove-size w-100'>X</button>
                </div>
            </div>";
        }
        ?>
    </div>

    <button type="button" id="btn-add-size" class="btn btn-primary mt-2">+ Thêm size</button>
</div>

<!-- SCRIPT THÊM / XOÁ SIZE -->
<script>
    document.getElementById("btn-add-size").addEventListener("click", () => {
        const container = document.getElementById("size-container");
        const div = document.createElement("div");
        div.classList.add("row", "g-2", "mb-2", "size-row");
        div.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="size[]" class="form-control" placeholder="Size" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="giaNhap[]" class="form-control" min="0" placeholder="Giá nhập" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="giaXuat[]" class="form-control" min="0" placeholder="Giá xuất" required>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger btn-remove-size w-100">X</button>
            </div>`;
        container.appendChild(div);
    });

    document.addEventListener("click", e => {
        if (e.target.classList.contains("btn-remove-size")) {
            e.target.closest(".size-row").remove();
        }
    });
</script>
        <!-- SUBMIT -->
        <div class="card-footer text-end">
            <a href="<?php echo APP_URL; ?>/Product/" class="btn btn-secondary">Quay lại</a>
            <input type="submit" class="btn btn-<?php echo isset($data['editItem']) ? 'warning' : 'success'; ?>"
                value="<?php echo isset($data['editItem']) ? 'Cập nhật' : 'Lưu'; ?>">
        </div>
    </div>
</form>
