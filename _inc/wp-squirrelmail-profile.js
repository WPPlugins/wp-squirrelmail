/**
 * @type Object jQuery No Conflict.
 */
var $ = jQuery.noConflict();

var autologin = {
    /**
     * Attached events to fields
     * @returns {void}
     */
    onReady: function() {
        // Check autologin element on ready
        autologin.changeElement();
        
        // Disable Autologin feature if username field is empty
        $( '#wp-squirrelmail-username').change( autologin.changeElement );
        
        // Disable Autologin feature if password field is empty
        $( '#wp-squirrelmail-password').change( autologin.changeElement );
    },
    
    /**
     * Disable or Enable Autologin.
     * Returns autologin element state
     * @returns {document@call;getElementById.changeElement.disable|Boolean}
     */
    changeElement: function() {
        var username = document.getElementById('wp-squirrelmail-username').value;
        var password = document.getElementById('wp-squirrelmail-password').value;
        var autologin = document.getElementById('wp-squirrelmail-autologin');
        var disable = false;
        
       if(username.replace(/\s+/g, '').length > 0 && password.replace(/\s+/g, '').length > 0) {
           autologin.disabled = false;
       } else {
           autologin.disabled = true;
           disable = true;
       }
       
       return disable;
    }
};

$( document ).ready( autologin.onReady );
