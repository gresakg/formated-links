 (function() {
    tinymce.create('tinymce.plugins.FLinks', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
            ed.addButton('see', {
                title : 'See also',
                image : url + '/relation_map.png',
                onclick: function() {
                    // Open window
                    ed.windowManager.open({
                        title: 'Recommend',
                        body: [
                            {type: 'textbox', name: 'url', label: 'URL'},
                            {type: 'textbox', name: 'title', label: 'Title'}
                        ],
                        onsubmit: function(e) {
                            // Insert content when the window form is submitted
                            ed.insertContent('[see url="' + e.data.url + '"]' + e.data.title + '[/see]');
                        }
                    });
                }

            });

            ed.addButton('ctabutton', {
                title : 'CTA Button',
                image : url + '/button.png',
                onclick: function() {
                    // Open window
                    ed.windowManager.open({
                        title: 'CTA Button',
                        body: [
                            {type: 'textbox', name: 'url', label: 'URL'},
                            {type: 'textbox', name: 'title', label: 'Title'}
                        ],
                        onsubmit: function(e) {
                            // Insert content when the window form is submitted
                            ed.insertContent('[ggcte url="' + e.data.url + '"]' + e.data.title + '[/ggcte]');
                        }
                    });
                }

            });
            
            
        },
 
        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },
 
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Formated Links',
                author : 'Gresak Gregor',
                authorurl : 'http://gresak.net',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                version : "0.1"
            };
        }
    });
 
    // Register plugin
    tinymce.PluginManager.add( 'FLinks', tinymce.plugins.FLinks );
})();