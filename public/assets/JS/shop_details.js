let quintity = document.getElementById('quintity').innerHTML;
let num = Number(quintity);

function increase() {
    num++;
    document.getElementById('quintity').innerHTML = num;
}

function decrease() {
    if (num > 1) {
        num--;
        document.getElementById('quintity').innerHTML = num;
    }
}

function addToCart(productId) {
    const quantity = document.getElementById('quintity').innerHTML;
    if (quantity <= 0) {
        alert('Please select quantity first');
        return;
    }
    
    // Send AJAX request to add to cart
    fetch('./add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${productId}&quantity=${quantity}`
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            alert('Product added to cart successfully!');
            // Update cart count
            location.reload();
            location.href = './index.php';
        } else {
            alert('Error adding product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding product to cart');
    });
}