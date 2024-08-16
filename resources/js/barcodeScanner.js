// public/js/barcodeScanner.js

document.addEventListener('DOMContentLoaded', function () {
    const barcodeInput = document.getElementById('barcodeInput');
    const barcodeScannerContainer = document.getElementById('barcodeScannerContainer');
    let scannedInventoryId = null;

    $('#barcodeScannerModal').on('show.bs.modal', function () {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: barcodeScannerContainer
            },
            decoder: {
                readers: ["code_128_reader"]
            }
        }, function (err) {
            if (err) {
                console.log(err);
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected(function (data) {
            barcodeInput.value = data.codeResult.code;
            Quagga.stop();
            $('#barcodeScannerModal').modal('hide');

            handleBarcodeScan(data.codeResult.code);
        });
    });

    $('#barcodeScannerModal').on('hidden.bs.modal', function () {
        Quagga.stop();
    });

    function handleBarcodeScan(barcode) {
        fetch(`/inventory/scan/${barcode}`)
            .then(response => response.json())
            .then(data => {
                if (data.inventory_id) {
                    scannedInventoryId = data.inventory_id;
                    alert(`Item: ${data.name}\nStock: ${data.available_quantity}`);
                    // Open the quantity input modal or directly input the quantity here
                    let quantity = prompt(`Enter quantity to be deducted for ${data.name} (max: ${data.available_quantity})`);
                    
                    if (quantity && quantity <= data.available_quantity) {
                        submitOutgoingByScan(barcode, quantity);
                    } else {
                        alert('Invalid quantity entered.');
                    }
                } else {
                    alert('Inventory not found.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function submitOutgoingByScan(barcode, quantity) {
        fetch(`/inventory/submitOutgoingByScan`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                barcode: barcode,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Outgoing item recorded successfully.');
                location.reload(); // Reload the page to update the inventory list
            } else {
                alert(data.error || 'An error occurred.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
