const discountProducts  = {
    methods: {
        getDiscount(price, discount) {
            let igv = 0.18,
                newPrice = parseInt(price)

            if (discount != 0) {
                newPrice = price - (price * (discount/ 100))
            }


            return (newPrice + (newPrice * igv)).toFixed(2)

        },
    }
}

export default discountProducts
