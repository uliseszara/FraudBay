// home
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
var validationCode;
var editProductId;

editPostButtons.forEach(function(elem) {
  elem.addEventListener("click", function () {
    editModal.classList.remove('hidden');
    var tokens = this.classList[1].split(/[ -]+/);
    validationCode = tokens[1];
    var classList = elem.parentElement.parentElement.parentElement.parentElement.classList;
    classList.forEach(function(elem2) {
      if(elem2.includes('product-id')) {
        editProductId = elem2;
      }
    })
    tokens = editProductId.split(/[ -]+/);
    editProductId = tokens[2];
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
    var userInput = document.querySelector('input[class="validationInput"]').value;

    if(userInput == validationCode) {
      var url = 'editFraud.php?productId='+editProductId;
      window.open(url,"_self");
    } else {
      document.querySelector('input[class="validationInput"]').value = '';
      editModal.classList.add('hidden');
    }
  });
}

var orderButtons = document.querySelectorAll('.orderButton');

if(orderButtons != null) {
  orderButtons.forEach(function(elem) {
    elem.addEventListener("click", function () {
      var classList = elem.parentElement.parentElement.classList;
      var productId;
      classList.forEach(function(elem2) {
        if(elem2.includes('product-id')) {
          productId = elem2;
        }
      })
      var tokens = productId.split(/[ -]+/);
      productId = tokens[2];
      var url = 'order.php?productId='+productId;
      window.open(url,"_self");
    })
  });
}


// post

var warehouseContainers = document.querySelectorAll('.warehouse-container');
var totalProductQuantity = document.querySelector('.totalProductQuantity');
var sum = 0;

if(warehouseContainers != null) {
  warehouseContainers.forEach(function(elem) {
    var checkbox = elem.querySelector('.product-warehouse-checkbox');
    var warehouseQuantitySection = elem.querySelector(".warehouse-quantity-faux, .warehouse-quantity");
    var quantityInput = elem.querySelector('.warehouse-product-quantityInput');
    checkbox.addEventListener("change", function() {
      if(this.checked) {
        warehouseQuantitySection.classList.remove('hidden');
        warehouseQuantitySection.classList.remove('warehouse-quantity-faux');
        warehouseQuantitySection.classList.add('warehouse-quantity');
        quantityInput.value = 0;
      } else {
        warehouseQuantitySection.classList.add('hidden');
        warehouseQuantitySection.classList.add('warehouse-quantity-faux');
        warehouseQuantitySection.classList.remove('warehouse-quantity');
        sum -= Number(quantityInput.value);
        totalProductQuantity.innerHTML = sum;
      }
    });

    quantityInput.addEventListener("change", function() {
      sum += Number(this.value);
      totalProductQuantity.innerHTML = sum;
    });
  });
}



// DB stuff
