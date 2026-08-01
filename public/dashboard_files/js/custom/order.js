$(document).ready(function () {
    //add product btn
    $(document).on("click", ".add-product-btn", function (e) {
        e.preventDefault();

        var btn = $(this);

        $.ajax({
            url: productQtyUrl,
            type: "GET",
            data: {
                id: btn.data("id"),
            },
            success: function (data) {
                if (!data) {
                    alert("لا يوجد كمية كافية في المخزون");
                    return;
                }

                var name = btn.data("name");
                var id = btn.data("id");
                var price = $.number(btn.data("price"), 2);

                btn.removeClass("btn-success").addClass("btn-default disabled");

                var html = `<tr data-id="${id}">
                    <td>${name}</td>
                    <td>
                        <input type="number"
                               name="products[${id}][quantity]"
                               data-price="${price}"
                               class="form-control input-sm product-quantity"
                               min="1"
                               value="1">
                    </td>
                    <td class="product-price">${price}</td>
                    <td>
                        <button class="btn btn-danger btn-sm remove-product-btn" data-id="${id}">
                            <span class="fa fa-trash"></span>
                        </button>
                    </td>
                </tr>`;

                $(".order-list").append(html);

                calculateTotal();
            },
        });
    });

    //disabled btn
    $("body").on("click", ".disabled", function (e) {
        e.preventDefault();
    }); //end of disabled

    //remove product btn
    $("body").on("click", ".remove-product-btn", function (e) {
        e.preventDefault();
        var id = $(this).data("id");

        $(this).closest("tr").remove();
        $("#product-" + id)
            .removeClass("btn-default disabled")
            .addClass("btn-success");

        //to calculate total price
        calculateTotal();
    }); //end of remove product btn

    //change product quantity
    $("body").on("keyup change", ".product-quantity", function () {
        var quantity = Number($(this).val()); //2
        var unitPrice = parseFloat($(this).data("price").replace(/,/g, "")); //150
        console.log(unitPrice);
        $(this)
            .closest("tr")
            .find(".product-price")
            .html($.number(quantity * unitPrice, 2));
        calculateTotal();
    }); //end of product quantity change

    //list all order products
    $(".order-products").on("click", function (e) {
        e.preventDefault();

        $("#loading").css("display", "flex");

        var url = $(this).data("url");
        var method = $(this).data("method");
        $.ajax({
            url: url,
            method: method,
            success: function (data) {
                $("#loading").css("display", "none");
                $("#order-product-list").empty();
                $("#order-product-list").append(data);
            },
        });
    }); //end of order products click
}); //end of document ready

//calculate the total
function calculateTotal() {
    var price = 0;

    $(".order-list .product-price").each(function (index) {
        price += parseFloat($(this).html().replace(/,/g, ""));
    }); //end of product price

    let discountPercent = Number($("#discount").val()) || 0;

    // منع إدخال أكثر من 100%
    discountPercent = Math.min(discountPercent, 100);

    let discountValue = price * (discountPercent / 100);

    price -= discountValue;

    let finalTotal = price;

    $(".total-price").html($.number(finalTotal, 2));
    $(".total-price-value").val(finalTotal.toFixed(2));

    //check if price > 0
    if (price > 0) {
        $("#add-order-form-btn").removeClass("disabled");
    } else {
        $("#add-order-form-btn").addClass("disabled");
    } //end of else
} //end of calculate total

function calculateInstallment() {
    // إجمالي المنتجات
    let price = 0;

    $(".product-quantity").each(function () {
        let qty = Number($(this).val()) || 0;
        let itemPrice =
            parseFloat($(this).data("price").toString().replace(/,/g, "")) || 0;

        price += qty * itemPrice;
    });

    let discountPercent = Number($("#discount").val()) || 0;

    // منع إدخال أكثر من 100%
    discountPercent = Math.min(discountPercent, 100);

    let discountValue = price * (discountPercent / 100);

    price -= discountValue;

    // بيانات التقسيط
    let start = Number($("#start").val()) || 0;
    let benefit = Number($("#benefit").val()) || 0;
    let installmentNumber = Number($("#installment_number").val()) || 0;

    let remaining = Math.max(price - start, 0);

    let totalAfterBenefit = remaining + (remaining * benefit) / 100;

    let installmentValue =
        installmentNumber > 0 ? totalAfterBenefit / installmentNumber : 0;

    $("#total_after_benefit").val(totalAfterBenefit.toFixed(2));
    $("#installment_value").val(installmentValue.toFixed(2));
    $(".total-price").html($.number(totalAfterBenefit, 2));
    $(".total-price-value").val(totalAfterBenefit);
}

// عند تغيير الكمية أو بيانات التقسيط
$(document).on(
    "input",
    ".product-quantity, #discount, #start, #benefit, #installment_number",
    function () {
        calculateTotal();
        calculateInstallment();
    },
);
