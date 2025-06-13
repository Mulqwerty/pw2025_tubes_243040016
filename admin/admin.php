<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Gallery Foto</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 15px 40px rgba(31, 38, 135, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        }

        .btn-danger:hover {
            box-shadow: 0 10px 30px rgba(238, 90, 82, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #51cf66, #40c057);
        }

        .btn-success:hover {
            box-shadow: 0 10px 30px rgba(64, 192, 87, 0.4);
        }

        .stats-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(31, 38, 135, 0.2);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.05));
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.1));
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .upload-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(31, 38, 135, 0.2);
        }

        .upload-area {
            border: 3px dashed #667eea;
            border-radius: 15px;
            padding: 50px;
            text-align: center;
            background: rgba(102, 126, 234, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .upload-area:hover {
            border-color: #764ba2;
            background: rgba(102, 126, 234, 0.1);
            transform: scale(1.02);
        }

        .upload-area.dragover {
            border-color: #51cf66;
            background: rgba(81, 207, 102, 0.1);
            transform: scale(1.05);
        }

        .upload-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 20px;
        }

        .upload-text {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .upload-subtext {
            color: #666;
            font-size: 14px;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .photo-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(31, 38, 135, 0.2);
            transition: all 0.3s ease;
            position: relative;
        }

        .photo-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(31, 38, 135, 0.3);
        }

        .photo-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-image:hover {
            transform: scale(1.05);
        }

        .photo-info {
            padding: 20px;
        }

        .photo-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .photo-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .photo-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 12px;
            color: #888;
        }

        .photo-actions {
            display: flex;
            gap: 10px;
        }

        .btn-small {
            padding: 8px 15px;
            font-size: 12px;
            border-radius: 15px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            margin: 5% auto;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #ff6b6b;
        }

        .preview-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 20px 25px;
            box-shadow: 0 10px 30px rgba(31, 38, 135, 0.3);
            z-index: 1001;
            transform: translateX(100%);
            transition: all 0.3s ease;
            min-width: 300px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            border-left: 4px solid #51cf66;
        }

        .notification.error {
            border-left: 4px solid #ff6b6b;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 64px;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            .header h1 {
                font-size: 24px;
            }

            .stats-bar {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 20px;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>
                <i class="fas fa-images"></i>
                Admin Gallery Foto
            </h1>
            <div class="header-actions">
                <button class="btn" onclick="openUploadModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Foto
                </button>
                <button class="btn btn-danger" onclick="deleteSelected()">
                    <i class="fas fa-trash"></i>
                    Hapus Terpilih
                </button>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number" id="total-photos">0</div>
                <div class="stat-label">Total Foto</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="selected-count">0</div>
                <div class="stat-label">Terpilih</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="storage-used">0 MB</div>
                <div class="stat-label">Storage Terpakai</div>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="gallery-grid" id="gallery-grid">
            <!-- Empty state will be shown if no photos -->
            <div class="empty-state" id="empty-state">
                <i class="fas fa-images"></i>
                <h3>Belum Ada Foto</h3>
                <p>Mulai dengan menambahkan foto pertama Anda</p>
                <button class="btn" onclick="openUploadModal()" style="margin-top: 20px;">
                    <i class="fas fa-plus"></i>
                    Tambah Foto Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Tambah Foto Baru</h2>
                <span class="close" onclick="closeUploadModal()">&times;</span>
            </div>
            <form id="uploadForm">
                <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                    <div class="upload-text">Klik atau seret foto ke sini</div>
                    <div class="upload-subtext">Mendukung JPG, PNG, GIF (Max 5MB)</div>
                    <input type="file" id="fileInput" class="file-input" accept="image/*" multiple>
                </div>
                <div class="form-group">
                    <label for="photoTitle">Judul Foto</label>
                    <input type="text" id="photoTitle" class="form-control" placeholder="Masukkan judul foto">
                </div>
                <div class="form-group">
                    <label for="photoDescription">Deskripsi</label>
                    <textarea id="photoDescription" class="form-control" rows="3" placeholder="Masukkan deskripsi foto"></textarea>
                </div>
                <div class="form-group">
                    <label for="photoCategory">Kategori</label>
                    <select id="photoCategory" class="form-control">
                        <option value="landscape">Landscape</option>
                        <option value="portrait">Portrait</option>
                        <option value="nature">Alam</option>
                        <option value="architecture">Arsitektur</option>
                        <option value="street">Street Photography</option>
                        <option value="macro">Macro</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px;">
                    <button type="button" class="btn" onclick="closeUploadModal()">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i>
                        Upload Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="previewTitle">Preview Foto</h2>
                <span class="close" onclick="closePreviewModal()">&times;</span>
            </div>
            <img id="previewImage" class="preview-image" src="" alt="">
            <div id="previewInfo"></div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification">
        <div id="notificationContent"></div>
    </div>

    <script>
        // Gallery data storage
        let galleryData = JSON.parse(localStorage.getItem('galleryData')) || [];
        let selectedPhotos = new Set();

        // Initialize gallery
        document.addEventListener('DOMContentLoaded', function() {
            loadGallery();
            updateStats();
            setupDragAndDrop();
        });

        // Setup drag and drop
        function setupDragAndDrop() {
            const uploadArea = document.querySelector('.upload-area');
            
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                handleFiles(files);
            });
        }

        // File input change handler
        document.getElementById('fileInput').addEventListener('change', function(e) {
            handleFiles(e.target.files);
        });

        // Handle file selection
        function handleFiles(files) {
            for (let file of files) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.querySelector('.upload-area').style.backgroundImage = `url(${e.target.result})`;
                        document.querySelector('.upload-area').style.backgroundSize = 'cover';
                        document.querySelector('.upload-area').style.backgroundPosition = 'center';
                    };
                    reader.readAsDataURL(file);
                    break; // Show only first image as preview
                }
            }
        }

        // Upload form submission
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('fileInput');
            const title = document.getElementById('photoTitle').value;
            const description = document.getElementById('photoDescription').value;
            const category = document.getElementById('photoCategory').value;

            if (!fileInput.files.length) {
                showNotification('Pilih file foto terlebih dahulu!', 'error');
                return;
            }

            // Process each selected file
            Array.from(fileInput.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const newPhoto = {
                            id: Date.now() + Math.random(),
                            title: title || file.name,
                            description: description,
                            category: category,
                            src: e.target.result,
                            filename: file.name,
                            size: file.size,
                            dateAdded: new Date().toISOString()
                        };

                        galleryData.push(newPhoto);
                        saveGalleryData();
                        loadGallery();
                        updateStats();
                        showNotification('Foto berhasil ditambahkan!', 'success');
                    };
                    reader.readAsDataURL(file);
                }
            });

            closeUploadModal();
        });

        // Load gallery
        function loadGallery() {
            const grid = document.getElementById('gallery-grid');
            const emptyState = document.getElementById('empty-state');

            if (galleryData.length === 0) {
                grid.innerHTML = '';
                grid.appendChild(emptyState);
                return;
            }

            emptyState.style.display = 'none';
            
            grid.innerHTML = galleryData.map(photo => `
                <div class="photo-card" data-id="${photo.id}">
                    <img src="${photo.src}" alt="${photo.title}" class="photo-image" onclick="previewPhoto(${photo.id})">
                    <div class="photo-info">
                        <div class="photo-title">${photo.title}</div>
                        <div class="photo-description">${photo.description || 'Tidak ada deskripsi'}</div>
                        <div class="photo-meta">
                            <span><i class="fas fa-tag"></i> ${photo.category}</span>
                            <span><i class="fas fa-calendar"></i> ${formatDate(photo.dateAdded)}</span>
                        </div>
                        <div class="photo-actions">
                            <button class="btn btn-small" onclick="toggleSelect(${photo.id})">
                                <i class="fas fa-check"></i>
                                <span id="select-text-${photo.id}">Pilih</span>
                            </button>
                            <button class="btn btn-small btn-danger" onclick="deleteSinglePhoto(${photo.id})">
                                <i class="fas fa-trash"></i>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Toggle photo selection
        function toggleSelect(photoId) {
            const card = document.querySelector(`[data-id="${photoId}"]`);
            const selectText = document.getElementById(`select-text-${photoId}`);
            
            if (selectedPhotos.has(photoId)) {
                selectedPhotos.delete(photoId);
                card.style.border = 'none';
                selectText.textContent = 'Pilih';
            } else {
                selectedPhotos.add(photoId);
                card.style.border = '3px solid #51cf66';
                selectText.textContent = 'Terpilih';
            }
            
            updateStats();
        }

        // Delete selected photos
        function deleteSelected() {
            if (selectedPhotos.size === 0) {
                showNotification('Pilih foto yang ingin dihapus!', 'error');
                return;
            }

            if (confirm(`Hapus ${selectedPhotos.size} foto terpilih?`)) {
                galleryData = galleryData.filter(photo => !selectedPhotos.has(photo.id));
                selectedPhotos.clear();
                saveGalleryData();
                loadGallery();
                updateStats();
                showNotification('Foto berhasil dihapus!', 'success');
            }
        }

        // Delete single photo
        function deleteSinglePhoto(photoId) {
            if (confirm('Hapus foto ini?')) {
                galleryData = galleryData.filter(photo => photo.id !== photoId);
                selectedPhotos.delete(photoId);
                saveGalleryData();
                loadGallery();
                updateStats();
                showNotification('Foto berhasil dihapus!', 'success');
            }
        }

        // Preview photo
        function previewPhoto(photoId) {
            const photo = galleryData.find(p => p.id === photoId);
            if (!photo) return;

            document.getElementById('previewTitle').textContent = photo.title;
            document.getElementById('previewImage').src = photo.src;
            document.getElementById('previewInfo').innerHTML = `
                <div style="margin-top: 20px;">
                    <p><strong>Deskripsi:</strong> ${photo.description || 'Tidak ada deskripsi'}</p>
                    <p><strong>Kategori:</strong> ${photo.category}</p>
                    <p><strong>Nama File:</strong> ${photo.filename}</p>
                    <p><strong>Ukuran:</strong> ${formatFileSize(photo.size)}</p>
                    <p><strong>Tanggal Ditambahkan:</strong> ${formatDate(photo.dateAdded)}</p>
                </div>
            `;
            
            document.getElementById('previewModal').style.display = 'block';
        }

        // Update statistics
        function updateStats() {
            document.getElementById('total-photos').textContent = galleryData.length;
            document.getElementById('selected-count').textContent = selectedPhotos.size;
            
            const totalSize = galleryData.reduce((sum, photo) => sum + (photo.size || 0), 0);
            document.getElementById('storage-used').textContent = formatFileSize(totalSize);
        }

        // Modal functions
        function openUploadModal() {
            document.getElementById('uploadModal').style.display = 'block';
            // Reset form
            document.getElementById('uploadForm').reset();
            document.querySelector('.upload-area').style.backgroundImage = 'none';
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').style.display = 'none';
        }

        function closePreviewModal() {
            document.getElementById('previewModal').style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const uploadModal = document.getElementById('uploadModal');
            const previewModal = document.getElementById('previewModal');
            
            if (event.target === uploadModal) {
                closeUploadModal();
            }
            if (event.target === previewModal) {
                closePreviewModal();
            }
        }

        // Notification system
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const content = document.getElementById('notificationContent');
            
            content.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" 
                       style="color: ${type === 'success' ? '#51cf66' : '#ff6b6b'}; font-size: 18px;"></i>
                    <span>${message}</span>
                </div>
            `;
            
            notification.className = `notification ${type} show`;
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Utility functions
        function saveGalleryData() {
            localStorage.setItem('galleryData', JSON.stringify(galleryData));
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        // Add some sample data if empty (for demo purposes)
        if (galleryData.length === 0) {
            // You can remove this section in production
            const samplePhotos = [
                {
                    id: 1,
                    title: "Sunset di Pantai",
                    description: "Pemandangan sunset yang indah di pantai selatan",
                    category: "landscape",
                    src: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300' viewBox='0 0 400 300'%3E%3Cdefs%3E%3ClinearGradient id='sunset' x1='0%25' y1='0%25' x2='0%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%23ff6b6b'/%3E%3Cstop offset='50%25' style='stop-color:%23ffa726'/%3E%3Cstop offset='100%25' style='stop-color:%23ffcc02'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='400' height='300' fill='url(%23sunset)'/%3E%3Ccircle cx='320' cy='80' r='40' fill='%23fff3e0'/%3E%3Cpath d='M0 200 Q200 180 400 200 L400 300 L0 300 Z' fill='%2325a3a3'/%3E%3Ctext x='200' y='250' text-anchor='middle' fill='white' font-family='Arial' font-size='16' font-weight='bold'%3ESunset Beach%3C/text%3E%3C/svg%3E",
                    filename: "sunset-beach.jpg",
                    size: 245760,
                    dateAdded: new Date().toISOString()
                }
            ];
            
            galleryData = samplePhotos;
            saveGalleryData();
            loadGallery();
            updateStats();
        }
    </script>
</body>
</html>