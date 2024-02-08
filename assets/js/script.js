// new DataTable('#example');

jQuery(document).ready(function($) {
// Initialize DataTable with checkbox column
var table = $('#example').DataTable({
    columnDefs: [
        {
            orderable: false,
            className: 'select-checkbox',
            targets: 0 // Indexes of the checkbox columns
        }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    }
});

// Handle "Select All" checkbox
$('#selectAll').on('change', function () {
    if ($(this).is(':checked')) {
        table.rows().select();
    } else {
        table.rows().deselect();
    }
});

$('#selectAllFooter').on('change', function () {
    if ($(this).is(':checked')) {
        table.rows().select();
    } else {
        table.rows().deselect();
    }
});



// Get the current URL path
var path = window.location.pathname.split("/").pop() || '/';

// Handle special case for home page
if (path === '/') {
    $('#sidebar a[href="/"]').parent().addClass('active');
} else {
    // Set active class for other pages
    $('#sidebar a[href="' + path + '"]').parent().addClass('active');
}

    // $('select').selectize({
    //     sortField: 'text'
    // });

    $(".invoiceForm-row1-col1-right").hide();

    $("#select-customer").on("change", function() {
      $(".invoiceForm-row1-col1-right2").show();
      $(".invoiceForm-row1-col1-right").hide();
      $(".invoiceForm-row1-col1-right-client-box").hide();
      $(".invoiceForm-row1-col1-righ-cancel-btn").show();
    });

    $("#newCustomerButton").on("click", function() {
      $(".invoiceForm-row1-col1-right2").hide();
      $(".invoiceForm-row1-col1-right").show();
      $(".invoiceForm-row1-col1-right-client-box").hide();
      $(".invoiceForm-row1-col1-righ-cancel-btn").show();
    });

    $(".invoiceForm-row1-col1-righ-cancel-btn").on("click", function() {
      $(".invoiceForm-row1-col1-righ-cancel-btn").hide();
      $(".invoiceForm-row1-col1-right-client-box").show();
      $(".invoiceForm-row1-col1-right2").hide();
      $(".invoiceForm-row1-col1-right").hide();

    });

    // create invoice popup
    $("#create-invoice-button").click(function (e) {
        $("#create-invoice-popup").show();
    });

    $(".close-invoice-popup,.cancel-invoice-btn").click(function (e) {    
        $("#create-invoice-popup").hide();
    });
     
    // view invoice of client
    
    $(".view-invoice").click(function (e) {
        $("#create-invoice-popup-view").show();
    });

    // cancel invoice 
    $(".cancel-invoice-btn,.close-invoice-popup").click(function (e) {    
        $("#create-invoice-popup-view").hide();
    });

    // Open the popup for add items
    $("#add-items-button").click(function (e) {
        $("#popup-form").show();
    });

    $(".close-popup").click(function (e) {    
        $("#popup-form").hide();
    });

     // Open the popup for view add items
     $(".table").on("click",".view-item-popup",function (e) {
        $("#popup-form-view").show();
    });
    //  $(".view-item-popup").click(function (e) {
    //     $("#popup-form-view").show();
    // });
    $(".cancel-invoice-btn,.close-invoice-popup").click(function (e) {
        $("#create-invoice-popup-view").hide();
    });

    $(".additem-discard-btn,.close-popup").click(function (e) {    
        $("#popup-form-view").hide();
    });

     // Discard button in add item
    $(".additem-discard-btn").click(function (e) {    
        $("#popup-form, #customer-popup,#customer-popup-view").hide();
    });
    
    // open pop up for add customer
    $("#add-customer-button").click(function (e) {
        $("#customer-popup").show();
    });

    $(".close-customer-popup").click(function (e) {    
        $("#customer-popup").hide();
    });

    // edit customer popup 
    $(".edit_customer-button").click(function (e) {
        $("#customer-popup-view").show();
    });
    $(".close-customer-popup").click(function (e) {    
        $("#customer-popup-view").hide();
    });

    // pop-up delete button
    $(".delete-item,.delete-invoice").click(function (e) {
        $("#delete-popup").show();
    });

    $(".close-delete-popup").click(function (e) {
        $("#delete-popup").hide();
    });
    
    $(".cancel-to-delete").click(function (e) {
        $("#delete-popup").hide();
    });

    // show update password
    $(".update-password").click(function (e) {
        $(".change-password-container").show();
        $(".admin-update-password-box").hide();
    });

    $(".password-discard-btn").click(function (e) {
        $(".change-password-container").hide();
        $(".admin-update-password-box").show();
    });



    $("#search-input").on("keyup",function (e) {
        $val = $(this).val();
        $keyup = $("div#example_filter input").val($val);
        $keyup.keyup();
    })

    // $("#add-row-btn").click(function (e) {
    //     var clone = $(".im-Trow:first-child").clone(true,true);
    //     var count = $('.im-Trow').length + 1;
    //     var count2 = $('.im-Trow').length + 2;

    // //  Generate a new unique ID for the clone
    //     var newID = 'im-Trow-' + ((count));
    //     clone.attr('id', newID);
    //     $(clone).appendTo("#im-Tbody");
    //    $(clone).find("input").val('');

    // });

        // display uploaded image in customer
        $('#fileInput').on('change', function () {
            displayImage();
        });

        function displayImage() {
            var fileInput = $('#fileInput')[0]; // Get the DOM element from jQuery object
            var uploadedImage = $('.uploaded-image');
            var fileLabel = $('.customer-img-file-label');
            var fileIcon = $('.customer-file-upload-icon');
            var labelText = $('.upload-label-text');

            if (fileInput.files.length > 0) {
                // Display the uploaded image
                var reader = new FileReader();
                reader.onload = function (e) {
                    uploadedImage.attr('src', e.target.result);
                };
                reader.readAsDataURL(fileInput.files[0]);

                // Hide file icon and label text
                fileIcon.hide();
                labelText.hide();
                fileLabel.addClass('image-uploaded'); // Optional: Add a class to apply additional styling
            }
        }
          
        // display uploaded company signature
            $('#sign-fileInput').on('change', function () {
                displayCompanySignature();
            });

            function displayCompanySignature() {
                var fileInput = $('#sign-fileInput')[0]; // Get the DOM element from jQuery object
                var uploadedImage = $('#uploaded-company-Signature');
                var fileIcon = $('.file-upload-company-icon');

                if (fileInput.files.length > 0) {
                    // Display the uploaded image
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        uploadedImage.attr('src', e.target.result);
                    };
                    reader.readAsDataURL(fileInput.files[0]);

                    // Hide file icon
                    fileIcon.hide();
                }
            }

            // display uploaded client signature
            $('#client-sign-fileInput').on('change', function () {
                displayClientSignature();
            });

            function displayClientSignature() {
                var fileInput = $('#client-sign-fileInput')[0]; // Get the DOM element from jQuery object
                var uploadedImage = $('#uploaded-client-Signature');
                var fileIcon = $('.file-upload-client-icon');

                if (fileInput.files.length > 0) {
                    // Display the uploaded image
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        uploadedImage.attr('src', e.target.result);
                    };
                    reader.readAsDataURL(fileInput.files[0]);

                    // Hide file icon
                    fileIcon.hide();
                }
            }

            // display uploaded client image
                $('.file-upload-client').on('change', function () {
                    displayClientImage();
                });

                function displayClientImage() {
                    var fileInput = $('.file-upload-client')[0]; // Get the DOM element from jQuery object
                    var uploadedImage = $('.uploaded-image');
                    var fileIcon = $('.customer-img');

                    if (fileInput.files.length > 0) {
                        // Display the uploaded image
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            uploadedImage.attr('src', e.target.result);
                        };
                        reader.readAsDataURL(fileInput.files[0]);

                        // Hide file icon
                        fileIcon.hide();
                    }
                }
        //    calculate total values of items
            
        
            // Add new row
            $('#add-row-btn').click(function() {
                addRow();
            });

            // var a = `<tr class="im-Trow">
            // <td>
            //     <select class="productSelect" name="product[]">
            //         <option value="product1">UI UX Design Charge</option>
            //         <option value="product2">Development Charge</option>
            //         <option value="product3">Marketing Charge</option>
            //     </select>
            // </td>
            // <td><input type="number" class="quantity" name="quantity[]" min="1" value="1"></td>
            // <td><input type="number" class="price" name="price[]" min="0" step="0.01" value="0.00"></td>
            // <td><input type="number" class="discount" name="discount[]" min="0" max="100" step="1" value="0"></td>
            // <td><input type="number" class="totalPrice" name="totalPrice[]" readonly=""></td>
            // <td><button class="remove-row"><img src="assets/images/close-circle-line.svg" alt=""></button></td>
            // </tr>`;           
                       
            // Function to update remove button visibility
                function updateRemoveButtonVisibility() {
                    var $tbody = $('#im-Tbody');
                    var $rows = $tbody.find('tr');
                    
                    if ($rows.length === 1) {
                        // If only one row, hide the remove button
                        $('.remove-row').hide();
                    } else {
                        // Show the remove button for more than one row
                        $('.remove-row').show();
                    }
                }

                // Remove row
            $(document).on('click', '.remove-row', function() {
                var $row = $(this).closest('tr');
                $row.remove();
                
                // Update remove button visibility
                updateRemoveButtonVisibility();
            
                calculateTotal();
            });

            updateRemoveButtonVisibility();

            // Calculate total on GST change
            $('#gst').on('input', function() {
                calculateTotal();
            });

            // Calculate total on input change in rows
            $(document).on('input', '.quantity, .price, .discount', function() {
                calculateRowTotal($(this).closest('tr'));
                calculateTotal();
            });
        
        function addRow() {
            const newRow = $('#im-Tbody .im-Trow:first').clone();
            newRow.find('select, input').val('').removeAttr('readonly');
            newRow.find('.totalPrice').attr('readonly', true);
            newRow.appendTo('#im-Tbody');
            updateRemoveButtonVisibility();
        }

        function calculateRowTotal(row) {
            const quantity = parseFloat(row.find('.quantity').val()) || 0;
            const price = parseFloat(row.find('.price').val()) || 0;
            const discount = parseFloat(row.find('.discount').val()) || 0;

            const total = quantity * price * (1 - discount / 100);
            row.find('.totalPrice').val(total.toFixed(2));
        }

        function calculateTotal() {
            let subtotal = 0;

            $('#im-Tbody .im-Trow').each(function() {
                const totalRowPrice = parseFloat($(this).find('.totalPrice').val()) || 0;
                subtotal += totalRowPrice;
            });

            const gstPercentage = parseFloat($('#gst').val()) || 0;
            const gst = (subtotal * gstPercentage) / 100;
            const total = subtotal + gst;

            $('#subtotal').val(subtotal.toFixed(2));
            $('#taxes').val(gst.toFixed(2));
            $('#alltotal').val(total.toFixed(2));
        }

       


        

       

});
    

