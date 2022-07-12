<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

 
function _calculator_template( $product) {
  ob_start(); ?>
  
    <?php  if( $product->Status != "Rental Only" && $product->Year >= $topYear && $product->Make != 'Brandt'):  ?>
        
          <div name="payAmt" class="red-tag">
            $<span class="calc-price" id="calc-price"></span>
            <span class="price-detail">Monthly Payment *OAC</span>
          </div>
      
          <div id="calculator">
            <h3>Customize Payments</h3>
            
            <form method="GET" name="calcForm" action="Calculator.html#Calc2" class="calc-Form" id="calc-Form">
            <input type="hidden" name="intRate"  class="intRate" size="5" maxlength="5" value="6.95"  readonly >
            <input type="hidden" name="equipAmt" size="11" maxlength="11" value="<?php echo  $product->TopPrice; ?>">
                  		
            <label>Term (Months) </label>
            <div class='btns'>
                        <label>
                          <input name='calc-payment-terms' type='radio' value='24'>
                            <span class='btn first'>24</span>
                          </input>
                        </label>
                        <label>
                          <input name='calc-payment-terms' type='radio' value='36'>
                            <span class='btn'>36</span>
                          </input>
                        </label>
                        <label>
                          <input   name='calc-payment-terms' type='radio' value='48'>
                            <span class='btn'>48</span>
                          </input>
                        </label>
                        <label>
                          <input checked='' name='calc-payment-terms' type='radio' value='60'>
                            <span class='btn'>60</span>
                          </input>
                        </label>
                      </div>
               
            <label>Frequency</label>
            <div class='btns'>
                    <label class="half">
                      <input checked='' name='calc-payment-frequency' type='radio' value='1'>
                        <span class='btn first'>Monthly</span>
                      </input>
                    </label>
                    <label class="half">
                      <input name='calc-payment-frequency' type='radio' value='0.461538'>
                        <span class='btn'>Bi-weekly</span>
                      </input>
                    </label>
                  </div>
                    
            <label>Interest <span>(APR)</span></label>
            <h4 class="intrestRate">6.95%</h4>
                
            <label>Cash Down (Min 10%)</label>
            <input type="text" name="depositAmt" style="margin-top: 2px;" size="11" maxlength="11" value="<?php echo  format_price( $product->TopPrice * 0.1 ) ; ?>">
            <input type="hidden" name="loanAmt" size="11" maxlength="11" value="<?php echo  $product->TopPrice - ($product->TopPrice * 0.1); ?>">
                    
            <div id="calc-form-error"></div>
                    
            <table class="finance-summary">
                        <tr>
                          <td>Financed amount</td>
                          <td class="finAmt right"></td>
                        </tr>
                        <tr>
                          <td class="summaryTotalBorder">Est. cost of credit</td>
                          <td class="finCredit right summaryTotalBorder"> </td>
                        </tr>
                         <tr>
                          <td>Est. total</td>
                          <td class="finTotal right"> </td>
                        </tr>
                     
                      </table>
            
            <p class="note">* Estimates provided by this calculator are only for reference. For a detailed quote please contact us.</p>
                   
          </form>
          </div>
        <?php endif; ?>

  
  
  <?php 
  return ob_get_clean();
}

 



 
 
 


 