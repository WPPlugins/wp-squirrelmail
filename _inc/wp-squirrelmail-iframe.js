/*
 * Copyright (C) 2016 Edgar Hernandez
 *
 * WP-SquirrelMail is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @type Object jQuery No Conflict.
 */
var $ = jQuery.noConflict();

var iFrame = {
    onReady: function() {
        $.post( ajaxurl, {
            action:   'wp_squirrelmail',
            dataType : "json"
        },
        function( data ) {
            if( data.response === true ) {
                $( '#wp-squirrelmail-div' ).empty();
                
                iFrame.createIframe( 'wp-squirrelmail-iframe', 'wp-squirrelmail-div' );
                iFrame.addLink('wp-squirrelmail-iframe', data.css);
                iFrame.writeContent( 'wp-squirrelmail-iframe', data.content );
                if( data.autologin === 1 ) {
                $( '#wp-squirrelmail-iframe' ).contents().find( '#wp-squirrelmail-form' ).submit();
                }
            } else {
                $( '#wp-squirrelmail-div' ).empty();
                // Advise user of error
                $( '#wp-squirrelmail-div' ).html( data.message );
                // print error message to console
                console.log('Could not update Login form');
            }
        });
    },
    
    /**
     * @description Create iframe element
     * @param {string} iframeId New Iframe Id
     * @param {string} elementId Target element Id
     * @returns {iframe}
     */
    createIframe: function(iframeId, elementId){
        var iframe = document.createElement("iframe");
        
        iframe.setAttribute("about", "blank");
        iframe.setAttribute("id", iframeId);
        iframe.style.width = "100%";
        iframe.style.height = "35em";
        document.getElementById(elementId).appendChild(iframe);
        
        return iframe;
    },
    
    /**
     * @description Writes data pass to the iFrame as a string
     * @param {string} iframeId
     * @param {string} content
     * @returns {void}
     */
    writeContent: function( iframeId, content ){
        var $body = $( "#" + iframeId ).contents().find( "body" );
        
        $body.append( content );
    },
    
    /**
     * 
     * @param {string} iframeId
     * @param {string} cssFile
     * @returns {void}
     */
    addLink: function(iframeId, cssFile) {
        var $head = $( "#" + iframeId ).contents().find("head");
        var url = cssFile;
        
        $head.append($("<link/>", { rel: "stylesheet", href: url, type: "text/css" } ));
    }
};

$( document ).ready( iFrame.onReady );
