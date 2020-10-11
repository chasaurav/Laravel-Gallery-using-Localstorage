let owlVar = $('.owl-carousel');
const owlConfig = {
	items: 1,
	nav: false,
	dots: false,
	margin: 10,
	loop: true,
	autoplay: false,
	autoplayHoverPause: true,
	autoplaySpeed: 200,
	touchDrag: true,
	mouseDrag: true,
};
let img = [];

// -Storing the images
// -------------------
document.querySelector('#fileInput').addEventListener('change', function () {
	const reader = new FileReader();
	reader.addEventListener('load', () => {
		let prevItem = JSON.parse(localStorage.getItem('imageArr'));
		if (prevItem) img = [...prevItem, {"imgName" : reader.result}];
		else img[0] = {"imgName" : reader.result};
		
		try {
		    localStorage.setItem('imageArr', JSON.stringify(img));
		    document.querySelector('.alertMsg').textContent = "Image saved successfully";
		    addImageToDOM();
		}catch (e) {
		    document.querySelector('.alertMsg').textContent = "Opps!!! Something went wrong.";
			alert("Local Storage is full, Please empty data.");
			console.log("Local Storage is full, Please empty data.");
		}
	});
	reader.readAsDataURL(this.files[0]);
	this.value = '';
});

// -Adding the images to the DOM
// ------------------------------
const addImageToDOM = () => {
	const recentImgData = JSON.parse(localStorage.getItem('imageArr'));
	let html = '';

	// -Some Validations to check if the data is present
	// -------------------------------------------------
	if (!recentImgData) return false;
	if (recentImgData.length == 0) {
		$('.item').remove();
		localStorage.removeItem('imageArr');
		img = [];
		return false;
	}

	// -Appending the images to the Carousel
	// -------------------------------------
	recentImgData.map(({imgName}, idx) => {
		html += `<div class="item">`;
		html += `<img src="${imgName}" alt="image_${idx}" data-idx="${idx}" class="imageItem" style="height: 80vh;">`;
		html += `</div>`;
		$('.dumpImg').html(html);
	});

	// -Re-INITing the carousel
	// -------------------------
	owlVar.owlCarousel('destroy');
	owlVar.owlCarousel(owlConfig);
}

addImageToDOM();

$(document).ready(function() {
	// -Delete any hover menu on mouse leave
	// --------------------------------------
	$(document).on('mouseleave', '.item', function() { $('.hoverMenu').remove(); });

	// -Creating a hover menu to delete the image
	// ------------------------------------------
	$(document).on('mouseenter', '.item', function() {
		let that = $(this);
		let html = `<div class="hoverMenu">`;
		html += `<button type="button" class="custButton">Delete Image</button>`;
		html += `</div>`;
		that.append(html);
	});

	// -Handeling on delete click listener
	// ------------------------------------
	$(document).on('click', '.custButton', function() {
		let that = $(this);
		let imageItem = that.parent().parent().find('img');
		let delIdx = imageItem.data('idx');

		if (confirm("Are you sure to delete this image ?")) {
			const recentImgData = JSON.parse(localStorage.getItem('imageArr'));
			let filtered = recentImgData.filter((elm, idx) => idx != delIdx && elm);
			localStorage.setItem('imageArr', JSON.stringify(filtered));
			addImageToDOM();
		}
	});

	// -Handeling on click listener to close modal
	// --------------------------------------------
	$('#closeModal').on('click', function() { $('.cust_modal').fadeOut('fast'); $('.alertMsg').text(''); });

	// -Handeling on click listener to show modal
	// -------------------------------------------
	$('#addNewImage').on('click', function() { $('.cust_modal').fadeIn('fast'); });

	// -Delete all the images from the local storage
	// ---------------------------------------------
	$('#deleteAllImage').on('click', function() {
		const recentImgData = JSON.parse(localStorage.getItem('imageArr'));
		if (!recentImgData){
			alert("Nothing to delete...");
			return false;
		}

		if (confirm("Are you sure to delete all images permanently ?")) {
			localStorage.removeItem('imageArr');
			img = [];
			$('.item').remove();
			alert("All images has been deleted.");
		}
	});
});