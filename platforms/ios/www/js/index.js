/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

function rewrite_links()
{
	if (typeof(device) == 'undefined')
	{
		device = {};
		device.platform = 'browser';
	}
	$('a').each(function()
	{
		var href = $(this).attr('href');
		if (href.indexOf('?') == -1) href += '?';
		else href += '&';
		new_href = href + 'app_platform=' + device.platform.toLowerCase();
		$(this).attr('href', new_href);
	});
	//$('a').click(function(event){alert($(this).attr('href')); event.preventDefault()});
}


var app = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicity call 'app.receivedEvent(...);'
    onDeviceReady: function() {
        app.receivedEvent('deviceready');
		    if ((parseFloat(device.version) >= 7.0) && (device.platform == 'iOS')) {
    	      document.body.classList.add('ios-fix-status-bar');
		    }
				rewrite_links();
		},

    // Update DOM on a Received Event
    receivedEvent: function(id) {
        console.log('Received Event: ' + id);
    }

    // showAlert: function (message, title) {
// 			if (navigator.notification) {
// 					navigator.notification.alert(message, null, title, 'OK');
// 			} else {
// 					alert(title ? (title + ": " + message) : message);
// 			}
// 		},
};