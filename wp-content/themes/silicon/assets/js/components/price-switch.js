/**
 * Price switch
*/

const priceSwitch = (() => {

  let swictherWrapper = document.querySelectorAll('.price-switch-wrapper');

  if (swictherWrapper.length <= 0) return;
  
  for (let i = 0; i < swictherWrapper.length; i++) {
    let swicther = swictherWrapper[i].querySelector('[data-bs-toggle="price"]');

    swicther.addEventListener('change', (e) => {
      let checkbox = e.currentTarget.querySelector('input[type="checkbox"]'),
          pricingTableSectionID = checkbox.dataset.target,
          pricingTableSection = document.getElementById( pricingTableSectionID ),
          monthlyPrice = [], annualPrice = [];

        if ( pricingTableSection ) {
          monthlyPrice = pricingTableSection.querySelectorAll('[data-monthly-price]'),
          annualPrice = pricingTableSection.querySelectorAll('[data-annual-price]');
        }

      for (let n = 0; n < monthlyPrice.length; n++) {
        if (checkbox.checked == true ) {
          monthlyPrice[n].classList.add('d-none');
        } else {
          monthlyPrice[n].classList.remove('d-none');
        }
      }

      for (let m = 0; m < monthlyPrice.length; m++) {
        if (checkbox.checked == true ) {
          annualPrice[m].classList.remove('d-none');
        } else {
          annualPrice[m].classList.add('d-none');
        }
      }
    });
  }
  
})();

export default priceSwitch;
