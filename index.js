var searchBox = document.querySelector('.header-search-input');

if(searchBox != null) {
  searchBox.addEventListener('input', handleSearch);
}

function handleSearch () {

  var searchWord = document.querySelector('.header-search-input').value;
  searchWord = searchWord.toLowerCase();
  var posts = document.querySelectorAll(".fraud-post, .fraud-post-faux");
  for(var i = 0; i < posts.length; i++) {
    var postTitle = posts[i].querySelector('.fraud-title').textContent;
    var postDescription = posts[i].querySelector('.fraud-description').textContent;
    postTitle = postTitle.toLowerCase();
    postDescription = postDescription.toLowerCase();

    if((postTitle.indexOf(searchWord) < 0) && (postDescription.indexOf(searchWord) < 0))
    {
        var c = posts[i].querySelectorAll('*');
        posts[i].classList.add('hidden');
        posts[i].classList.add('fraud-post-faux');
        for (var i2 = 0; i2 < c.length; i2++) {
          c[i2].classList.add('hidden');
        }
        posts[i].classList.remove('fraud-post');
    }
    else
    {
        var c = posts[i].querySelectorAll('*');
        posts[i].classList.remove('hidden');
        posts[i].classList.remove('fraud-post-faux');
        for (var i2 = 0; i2 < c.length; i2++) {
          c[i2].classList.remove('hidden');
        }
        posts[i].classList.add('fraud-post');
    }
  }
}

var editPostButtons = document.querySelectorAll('.edit-fraud-post-btn');
var editModal = document.querySelector('.editModal');

editPostButtons.forEach(function(elem) {
  elem.addEventListener("click", function () {
    editModal.classList.remove('hidden');
  })
});

var cancelButton = document.querySelector('.cancelButton');
var validateButton = document.querySelector('.validateButton');

if(cancelButton != null) {
  cancelButton.addEventListener("click", function () {
    editModal.classList.add('hidden');
  });
}

if(validateButton != null) {
  validateButton.addEventListener("click", function () {
    window.open("editFraud.html","_self");
  });
}

var createPostButton = document.querySelector('.createPostSubmit');
var codeModal = document.querySelector('.codeModal');

if(createPostButton != null) {
  createPostButton.addEventListener("click", function () {
    codeModal.classList.remove('hidden');
  });

  var codeModalContinueButton = document.querySelector('.codeModalContinueButton');

  codeModalContinueButton.addEventListener('click', function () {
    window.open("index.html","_self");
  });
}

var warehouseContainers = document.querySelectorAll('.warehouse-container');

if(warehouseContainers != null) {
  warehouseContainers.forEach(function(elem) {
    var checkbox = elem.querySelector('.product-warehouse-checkbox');
    var warehouseQuantitySection = elem.querySelector(".warehouse-quantity-faux, .warehouse-quantity");
    checkbox.addEventListener("change", function() {
      if(this.checked) {
        warehouseQuantitySection.classList.remove('hidden');
        warehouseQuantitySection.classList.remove('warehouse-quantity-faux');
        warehouseQuantitySection.classList.add('warehouse-quantity');
      } else {
        warehouseQuantitySection.classList.add('hidden');
        warehouseQuantitySection.classList.add('warehouse-quantity-faux');
        warehouseQuantitySection.classList.remove('warehouse-quantity');
      }
    });
  });
}
