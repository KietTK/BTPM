<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Thư viện</title>
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
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .header h1 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .tab-btn {
            background: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .tab-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        .tab-btn.active {
            background: #667eea;
            color: white;
        }
        
        .content-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: none;
        }
        
        .content-section.active {
            display: block;
            animation: fadeIn 0.5s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s;
        }
        
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-danger {
            background: #e74c3c;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .book-list {
            display: grid;
            gap: 20px;
            margin-top: 20px;
        }
        
        .book-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #667eea;
            transition: all 0.3s;
        }
        
        .book-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .book-card h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .book-info {
            color: #666;
            margin: 5px 0;
        }
        
        .book-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }
        
        .book-actions button {
            padding: 8px 16px;
            font-size: 14px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-card h3 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .stat-card p {
            opacity: 0.9;
        }
        
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-box input {
            flex: 1;
        }
        
        .record-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 5px solid #f39c12;
        }
        
        .record-card.returned {
            border-left-color: #27ae60;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Hệ thống Quản lý Thư viện</h1>
            <p>Ứng dụng demo cho báo cáo môn Bảo trì Phần mềm</p>
        </div>
        
        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('add')">Thêm sách</button>
            <button class="tab-btn" onclick="showTab('list')">Danh sách sách</button>
            <button class="tab-btn" onclick="showTab('borrow')">Mượn sách</button>
            <button class="tab-btn" onclick="showTab('return')">Trả sách</button>
            <button class="tab-btn" onclick="showTab('stats')">Thống kê</button>
        </div>
        
        <!-- Thêm sách -->
        <div id="add" class="content-section active">
            <h2>➕ Thêm sách mới</h2>
            <div id="addAlert"></div>
            <form onsubmit="addBook(event)">
                <div class="form-group">
                    <label>Tên sách *</label>
                    <input type="text" id="addTitle" required>
                </div>
                <div class="form-group">
                    <label>Tác giả *</label>
                    <input type="text" id="addAuthor" required>
                </div>
                <div class="form-group">
                    <label>ISBN *</label>
                    <input type="text" id="addIsbn" required>
                </div>
                <div class="form-group">
                    <label>Số lượng *</label>
                    <input type="number" id="addQuantity" min="1" required>
                </div>
                <button type="submit" class="btn">Thêm sách</button>
            </form>
        </div>
        
        <!-- Danh sách sách -->
        <div id="list" class="content-section">
            <h2>📖 Danh sách sách</h2>
            <div class="search-box">
                <input type="text" id="searchKeyword" placeholder="Tìm kiếm...">
                <select id="searchType">
                    <option value="all">Tất cả</option>
                    <option value="title">Tên sách</option>
                    <option value="author">Tác giả</option>
                    <option value="isbn">ISBN</option>
                </select>
                <button class="btn" onclick="searchBooks()">Tìm kiếm</button>
            </div>
            <div id="bookList" class="book-list"></div>
        </div>
        
        <!-- Mượn sách -->
        <div id="borrow" class="content-section">
            <h2>📤 Mượn sách</h2>
            <div id="borrowAlert"></div>
            <form onsubmit="borrowBook(event)">
                <div class="form-group">
                    <label>Chọn sách *</label>
                    <select id="borrowBookId" required onchange="updateMaxBorrowQuantity()">
                        <option value="">-- Chọn sách --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Họ tên người mượn *</label>
                    <input type="text" id="borrowerName" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="borrowerEmail" required>
                </div>
                <div class="form-group">
                    <label>Số lượng *</label>
                    <input type="number" id="borrowQuantity" min="1" max="5" value="1" required>
                    <small id="availableInfo" style="color: #666;"></small>
                </div>
                <button type="submit" class="btn">Mượn sách</button>
            </form>
        </div>
        
        <!-- Trả sách -->
        <div id="return" class="content-section">
            <h2>📥 Trả sách</h2>
            <div id="returnAlert"></div>
            <form onsubmit="returnBook(event)">
                <div class="form-group">
                    <label>Chọn bản ghi mượn sách *</label>
                    <select id="returnRecordId" required>
                        <option value="">-- Chọn bản ghi --</option>
                    </select>
                </div>
                <button type="submit" class="btn">Trả sách</button>
            </form>
            <div id="borrowRecordsList" style="margin-top: 30px;"></div>
        </div>
        
        <!-- Thống kê -->
        <div id="stats" class="content-section">
            <h2>📊 Thống kê</h2>
            <div id="statsContent" class="stats-grid"></div>
        </div>
    </div>

    <script>
        // Dữ liệu mô phỏng
        let books = [];
        let borrowRecords = [];
        let nextId = 1;
        let nextRecordId = 1;
        let editingBookId = null;

        // Khởi tạo dữ liệu mẫu
        function initSampleData() {
            books = [
                {id: 1, title: "Lập trình PHP cơ bản", author: "Nguyễn Văn A", isbn: "ISBN-001", quantity: 10, available: 10},
                {id: 2, title: "Cơ sở dữ liệu MySQL", author: "Trần Thị B", isbn: "ISBN-002", quantity: 8, available: 6},
                {id: 3, title: "Thiết kế web responsive", author: "Lê Văn C", isbn: "ISBN-003", quantity: 5, available: 5}
            ];
            nextId = 4;
            
            borrowRecords = [
                {
                    id: 1, 
                    book_id: 2, 
                    borrower_name: "Phạm Thị D", 
                    borrower_email: "phamd@email.com",
                    quantity: 2,
                    borrow_date: new Date().toISOString(),
                    due_date: new Date(Date.now() + 14*24*60*60*1000).toISOString().split('T')[0],
                    return_date: null,
                    status: 'borrowed'
                }
            ];
            nextRecordId = 2;
        }
        
        initSampleData();

        // Chuyển tab
        function showTab(tabName) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById(tabName).classList.add('active');
            
            if (tabName === 'list') {
                displayBooks();
            } else if (tabName === 'borrow') {
                updateBorrowBookList();
            } else if (tabName === 'return') {
                updateReturnRecordList();
                displayBorrowRecords();
            } else if (tabName === 'stats') {
                displayStats();
            }
        }

        // Thêm sách
        function addBook(e) {
            e.preventDefault();
            
            const title = document.getElementById('addTitle').value;
            const author = document.getElementById('addAuthor').value;
            const isbn = document.getElementById('addIsbn').value;
            const quantity = parseInt(document.getElementById('addQuantity').value);
            
            if (!title || !author || !isbn) {
                showAlert('addAlert', 'Thiếu thông tin sách', 'error');
                return;
            }
            if (quantity <= 0) {
                showAlert('addAlert', 'Số lượng phải lớn hơn 0', 'error');
                return;
            }

            if (books.find(b => b.isbn === isbn)) {
                showAlert('addAlert', 'ISBN đã tồn tại trong hệ thống', 'error');
                return;
            }

            const newBook = {
                id: nextId++,
                title, author, isbn,
                quantity,
                available: quantity
            };
            books.push(newBook);
            showAlert('addAlert', 'Thêm sách thành công!', 'success');
            e.target.reset();
            displayBooks();
        }

        // Hiển thị danh sách sách
        function displayBooks(filteredBooks = null) {
            const bookList = document.getElementById('bookList');
            const booksToShow = filteredBooks || books;
            
            if (booksToShow.length === 0) {
                bookList.innerHTML = '<p style="text-align: center; color: #999;">Không có sách nào</p>';
                return;
            }
            
            bookList.innerHTML = booksToShow.map(book => `
                <div class="book-card">
                    <h3>${book.title}</h3>
                    <p class="book-info"><strong>Tác giả:</strong> ${book.author}</p>
                    <p class="book-info"><strong>ISBN:</strong> ${book.isbn}</p>
                    <p class="book-info"><strong>Tổng số:</strong> ${book.quantity} | <strong>Còn lại:</strong> ${book.available}</p>
                    <div class="book-actions">
                        <button class="btn" onclick="editBook(${book.id})">Sửa</button>
                        <button class="btn btn-danger" onclick="deleteBook(${book.id})">Xóa</button>
                    </div>
                </div>
            `).join('');
        }

        // Sửa sách
        function editBook(id) {
            const book = books.find(b => b.id === id);
            if (!book) return;

            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
            document.querySelector('.tab-btn').classList.add('active');
            document.getElementById('add').classList.add('active');

            document.getElementById('addTitle').value = book.title;
            document.getElementById('addAuthor').value = book.author;
            document.getElementById('addIsbn').value = book.isbn;
            document.getElementById('addQuantity').value = book.quantity;

            editingBookId = id;

            const btn = document.querySelector('#add .btn');
            btn.textContent = 'Cập nhật sách';
            btn.style.background = '#f39c12';
        }

        // Cập nhật sách
        function updateBook(e) {
            e.preventDefault();

            if (!editingBookId) return;

            const title = document.getElementById('addTitle').value;
            const author = document.getElementById('addAuthor').value;
            const isbn = document.getElementById('addIsbn').value;
            const quantity = parseInt(document.getElementById('addQuantity').value);

            const book = books.find(b => b.id === editingBookId);
            if (!book) return;

            const borrowedCount = book.quantity - book.available;
            if (quantity < borrowedCount) {
                showAlert('addAlert', `Không thể giảm số lượng xuống ${quantity}. Hiện đang có ${borrowedCount} cuốn đang mượn.`, 'error');
                return;
            }

            book.title = title;
            book.author = author;
            book.isbn = isbn;
            book.available = quantity - borrowedCount;
            book.quantity = quantity;

            showAlert('addAlert', 'Cập nhật sách thành công!', 'success');

            document.querySelector('#add form').reset();
            const btn = document.querySelector('#add .btn');
            btn.textContent = 'Thêm sách';
            btn.style.background = '#667eea';
            editingBookId = null;

            displayBooks();
        }

        // Xóa sách
        function deleteBook(bookId) {
            const book = books.find(b => b.id === bookId);
            if (!book) return;
            const borrowedCount = book.quantity - book.available;
            if (borrowedCount > 0) {
                alert(`Không thể xóa. Còn ${borrowedCount} cuốn đang được mượn`);
                return;
            }
            if (confirm(`Bạn có chắc muốn xóa sách "${book.title}"?`)) {
                books = books.filter(b => b.id !== bookId);
                displayBooks();
            }
        }

        // Tìm kiếm sách
        function searchBooks() {
            const keyword = document.getElementById('searchKeyword').value.toLowerCase();
            const searchType = document.getElementById('searchType').value;
            if (!keyword) {
                displayBooks();
                return;
            }
            const results = books.filter(book => {
                switch (searchType) {
                    case 'title': return book.title.toLowerCase().includes(keyword);
                    case 'author': return book.author.toLowerCase().includes(keyword);
                    case 'isbn': return book.isbn.toLowerCase().includes(keyword);
                    default:
                        return book.title.toLowerCase().includes(keyword) ||
                               book.author.toLowerCase().includes(keyword) ||
                               book.isbn.toLowerCase().includes(keyword);
                }
            });
            displayBooks(results);
        }

        // Cập nhật danh sách sách có thể mượn
        function updateBorrowBookList() {
            const select = document.getElementById('borrowBookId');
            select.innerHTML = '<option value="">-- Chọn sách --</option>' + 
                books.filter(b => b.available > 0).map(book => 
                    `<option value="${book.id}">${book.title} (Còn: ${book.available})</option>`
                ).join('');
        }

        // Cập nhật số lượng tối đa có thể mượn
        function updateMaxBorrowQuantity() {
            const bookId = parseInt(document.getElementById('borrowBookId').value);
            const quantityInput = document.getElementById('borrowQuantity');
            const availableInfo = document.getElementById('availableInfo');
            
            if (!bookId) {
                quantityInput.max = 5;
                availableInfo.textContent = '';
                return;
            }
            
            const book = books.find(b => b.id === bookId);
            if (book) {
                const maxBorrow = Math.min(book.available, 5);
                quantityInput.max = maxBorrow;
                quantityInput.value = Math.min(quantityInput.value, maxBorrow);
                availableInfo.textContent = `Số lượng có sẵn: ${book.available} (Tối đa mượn: ${maxBorrow})`;
            }
        }

        // Mượn sách - CẬP NHẬT SỐ LƯỢNG
        function borrowBook(e) {
            e.preventDefault();
            
            const bookId = parseInt(document.getElementById('borrowBookId').value);
            const borrowerName = document.getElementById('borrowerName').value;
            const borrowerEmail = document.getElementById('borrowerEmail').value;
            const quantity = parseInt(document.getElementById('borrowQuantity').value);
            
            const book = books.find(b => b.id === bookId);
            if (!book) {
                showAlert('borrowAlert', 'Sách không tồn tại', 'error');
                return;
            }
            
            if (quantity > book.available) {
                showAlert('borrowAlert', `Chỉ còn ${book.available} cuốn. Không thể mượn ${quantity} cuốn`, 'error');
                return;
            }
            
            if (quantity <= 0) {
                showAlert('borrowAlert', 'Số lượng phải lớn hơn 0', 'error');
                return;
            }
            
            // GIẢM SỐ LƯỢNG SÁCH CÒN LẠI
            book.available -= quantity;
            
            const borrowDate = new Date();
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + 14);
            
            const newRecord = {
                id: nextRecordId++,
                book_id: bookId,
                borrower_name: borrowerName,
                borrower_email: borrowerEmail,
                quantity: quantity,
                borrow_date: borrowDate.toISOString(),
                due_date: dueDate.toISOString().split('T')[0],
                return_date: null,
                status: 'borrowed'
            };
            
            borrowRecords.push(newRecord);
            
            showAlert('borrowAlert', `Mượn ${quantity} cuốn "${book.title}" thành công! Hạn trả: ${dueDate.toLocaleDateString('vi-VN')}`, 'success');
            
            e.target.reset();
            updateBorrowBookList();
            displayBooks();
        }

        // Cập nhật danh sách bản ghi trả sách
        function updateReturnRecordList() {
            const select = document.getElementById('returnRecordId');
            const activeRecords = borrowRecords.filter(r => r.status === 'borrowed');
            
            select.innerHTML = '<option value="">-- Chọn bản ghi --</option>' + 
                activeRecords.map(record => {
                    const book = books.find(b => b.id === record.book_id);
                    return `<option value="${record.id}">${book.title} - ${record.borrower_name} (${record.quantity} cuốn)</option>`;
                }).join('');
        }

        // Hiển thị danh sách bản ghi mượn
        function displayBorrowRecords() {
            const container = document.getElementById('borrowRecordsList');
            
            if (borrowRecords.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #999;">Chưa có bản ghi mượn sách</p>';
                return;
            }
            
            container.innerHTML = '<h3 style="margin-bottom: 15px;">Danh sách bản ghi mượn sách</h3>' +
                borrowRecords.map(record => {
                    const book = books.find(b => b.id === record.book_id);
                    const isReturned = record.status === 'returned';
                    
                    return `
                        <div class="record-card ${isReturned ? 'returned' : ''}">
                            <h4>${book.title}</h4>
                            <p><strong>Người mượn:</strong> ${record.borrower_name}</p>
                            <p><strong>Email:</strong> ${record.borrower_email}</p>
                            <p><strong>Số lượng:</strong> ${record.quantity} cuốn</p>
                            <p><strong>Ngày mượn:</strong> ${new Date(record.borrow_date).toLocaleDateString('vi-VN')}</p>
                            <p><strong>Hạn trả:</strong> ${record.due_date}</p>
                            ${isReturned ? `<p><strong>Ngày trả:</strong> ${new Date(record.return_date).toLocaleDateString('vi-VN')}</p>` : ''}
                            <p><strong>Trạng thái:</strong> <span style="color: ${isReturned ? '#27ae60' : '#f39c12'}; font-weight: bold;">${isReturned ? 'Đã trả' : 'Đang mượn'}</span></p>
                        </div>
                    `;
                }).join('');
        }

        // Trả sách - TĂNG LẠI SỐ LƯỢNG
        function returnBook(e) {
            e.preventDefault();
            
            const recordId = parseInt(document.getElementById('returnRecordId').value);
            const record = borrowRecords.find(r => r.id === recordId);
            
            if (!record) {
                showAlert('returnAlert', 'Không tìm thấy bản ghi', 'error');
                return;
            }
            
            if (record.status === 'returned') {
                showAlert('returnAlert', 'Sách này đã được trả trước đó', 'error');
                return;
            }
            
            // TĂNG LẠI SỐ LƯỢNG SÁCH CÒN LẠI
            const book = books.find(b => b.id === record.book_id);
            book.available += record.quantity;
            
            record.return_date = new Date().toISOString();
            record.status = 'returned';
            
            showAlert('returnAlert', `Trả ${record.quantity} cuốn "${book.title}" thành công!`, 'success');
            
            e.target.reset();
            updateReturnRecordList();
            displayBorrowRecords();
            displayBooks();
        }

        // Hiển thị thống kê
        function displayStats() {
            const totalBooks = books.reduce((sum, b) => sum + b.quantity, 0);
            const availableBooks = books.reduce((sum, b) => sum + b.available, 0);
            const borrowedBooks = totalBooks - availableBooks;
            const activeBorrowRecords = borrowRecords.filter(r => r.status === 'borrowed').length;
            
            document.getElementById('statsContent').innerHTML = `
                <div class="stat-card">
                    <h3>${books.length}</h3>
                    <p>Tổng số đầu sách</p>
                </div>
                <div class="stat-card">
                    <h3>${totalBooks}</h3>
                    <p>Tổng số sách</p>
                </div>
                <div class="stat-card">
                    <h3>${availableBooks}</h3>
                    <p>Sách có sẵn</p>
                </div>
                <div class="stat-card">
                    <h3>${borrowedBooks}</h3>
                    <p>Sách đang mượn</p>
                </div>
                <div class="stat-card">
                    <h3>${activeBorrowRecords}</h3>
                    <p>Bản ghi đang mượn</p>
                </div>
                <div class="stat-card">
                    <h3>${borrowRecords.length}</h3>
                    <p>Tổng bản ghi</p>
                </div>
            `;
        }

        // Hiển thị thông báo
        function showAlert(containerId, message, type) {
            const container = document.getElementById(containerId);
            container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            setTimeout(() => container.innerHTML = '', 4000);
        }

        // Khởi chạy
        displayBooks();
    </script>
</body>
</html>