// Xử lý menu chính và submenu
const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top > li > a');

allSideMenu.forEach(item => {
	const li = item.parentElement;

	item.addEventListener('click', function (e) {
		// Nếu là menu có submenu thì toggle submenu
		if (li.classList.contains('has-submenu')) {
			e.preventDefault(); // Ngăn điều hướng nếu có submenu
			const isActive = li.classList.contains('active');

			// Ẩn tất cả các submenu khác trước
			document.querySelectorAll('#sidebar .side-menu.top > li.has-submenu').forEach(i => {
				i.classList.remove('active');
			});

			// Toggle chính mục đang click
			if (!isActive) {
				li.classList.add('active');
			}
		} else {
			// Không phải submenu: xóa active khỏi tất cả các mục
			document.querySelectorAll('#sidebar .side-menu.top > li').forEach(i => {
				i.classList.remove('active');
			});
			li.classList.add('active');
		}
	});
});


// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

if (menuBar && sidebar) {
	menuBar.addEventListener('click', function () {
		sidebar.classList.toggle('hide');
	});
}


// TOGGLE SEARCH (Mobile)
const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

if (searchButton) {
	searchButton.addEventListener('click', function (e) {
		if (window.innerWidth < 576) {
			e.preventDefault();
			searchForm.classList.toggle('show');
			if (searchForm.classList.contains('show')) {
				searchButtonIcon.classList.replace('bx-search', 'bx-x');
			} else {
				searchButtonIcon.classList.replace('bx-x', 'bx-search');
			}
		}
	});
}


// Đóng sidebar trên thiết bị nhỏ khi load
if (window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if (window.innerWidth > 576) {
	searchButtonIcon?.classList.replace('bx-x', 'bx-search');
	searchForm?.classList.remove('show');
}


// Responsive search toggle reset khi resize
window.addEventListener('resize', function () {
	if (this.innerWidth > 576) {
		searchButtonIcon?.classList.replace('bx-x', 'bx-search');
		searchForm?.classList.remove('show');
	}
});


// DARK MODE TOGGLE
const switchMode = document.getElementById('switch-mode');

if (switchMode) {
	switchMode.addEventListener('change', function () {
		if (this.checked) {
			document.body.classList.add('dark');
		} else {
			document.body.classList.remove('dark');
		}
	});
}
