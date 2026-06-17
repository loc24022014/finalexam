<?php
// app/helpers/UploadHelper.php
class UploadHelper {
    private static $uploadDir = 'public/uploads/products/';
    private static $allowedTypes = ['image/jpeg','image/png','image/webp','image/gif'];
    private static $maxSize = 5 * 1024 * 1024; // 5MB

    public static function uploadImage($file, $oldImage = null) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Không có file được tải lên hoặc có lỗi xảy ra.'];
        }

        if (!in_array($file['type'], self::$allowedTypes)) {
            return ['success' => false, 'error' => 'Chỉ chấp nhận file ảnh (JPG, PNG, WEBP, GIF).'];
        }

        if ($file['size'] > self::$maxSize) {
            return ['success' => false, 'error' => 'File ảnh không được vượt quá 5MB.'];
        }

        // Tạo thư mục nếu chưa có
        if (!is_dir(self::$uploadDir)) {
            mkdir(self::$uploadDir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('xe_', true) . '.' . strtolower($ext);
        $destination = self::$uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Xóa ảnh cũ nếu có
            if ($oldImage && file_exists(self::$uploadDir . $oldImage)) {
                unlink(self::$uploadDir . $oldImage);
            }
            return ['success' => true, 'filename' => $filename];
        }

        return ['success' => false, 'error' => 'Không thể lưu file ảnh.'];
    }

    public static function deleteImage($filename) {
        if ($filename && file_exists(self::$uploadDir . $filename)) {
            unlink(self::$uploadDir . $filename);
        }
    }

    public static function getImageUrl($filename) {
        if ($filename && file_exists(self::$uploadDir . $filename)) {
            return BASE_URL . '/public/uploads/products/' . $filename;
        }
        return BASE_URL . '/public/images/no-image.png';
    }
}
