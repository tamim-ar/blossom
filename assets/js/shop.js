function addToCart(flowerId) {
    fetch('api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            flower_id: flowerId,
            action: 'add'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Added to cart!');
        }
    })
    .catch(error => console.error('Error:', error));
}
