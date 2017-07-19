(function($) {
    $.evolveMerchandiser = function(t, dragula, opts) {
        this.params = $.extend({
            baseUrl: '',
            buttonReindex: '',
            element: t,
            form_key: '',
            cateID: 0
        }, opts);

        this.func = function() {
            /**
             * evolRoot
             * @type this
             */
            var evolRoot = this;

            /**
             * dragula
             * @type object of dragula
             */
            this.dragula = '';

            /**
             * items
             * @type []
             */
            this.items = {};

            /**
             * initialize
             * @return null
             */
            this.initialize = function() {
                if (typeof dragula == 'undefined') {
                    alert('Dragula library is missing');
                    return false;
                }
                this.dragula = dragula([t]);
                this.events();
            };

            /**
             * events
             * Define the events
             * @return null
             */
            this.events = function() {
                var that = this;
                // Button reindex all click
                if ('params' in evolRoot && 'buttonReindex' in evolRoot.params && evolRoot.params.buttonReindex != '') {
                    $(evolRoot.params.buttonReindex).click(function() { that.updatePositionForAllProducts(); });
                }
                // Dragula events
                this.dragula.on('drop', function(el, target, source, sibling) {
                    that.drop(el, source);
                });

                $(document).on('change', '.evol-merchan-item input.merchandiser_item_position', function() {
                    var parent = $(this).closest('.evol-merchan-item');
                    if (parent.length) {
                        that.move(parent, $(this).val());
                    }
                });
            };

            /**
             * itemUpdate
             * Update information for item
             * @param object el
             * @param int position
             * @return null
             */
            this.itemUpdate = function(el, position) {
                // Update position new
                $(el).find('.position .merchandiser_item_position').val(position);
                // Push item to items
                this.items[$(el).data('id')] = position;
            };

            /**
             * drop
             * The item update position when user change item's position
             * @param object ele
             * @param object[] source
             * @return null
             */
            this.drop = function(ele, source) {
                var childs = $(source).children('.ui-sortable-handle, .evol-merchan-item'),
                    that = this;
                $.each(childs, function(pos, ele) {
                    that.itemUpdate(ele, (pos + 1));
                }).promise().done(function() {
                    that.save();
                });
            };

            /**
             * move
             * @param oject ele
             * @param int ind
             * @return null
             */
            this.move = function(ele, ind) {
                if ('params' in evolRoot && 'element' in evolRoot.params && evolRoot.params.element != '' && $(evolRoot.params.element).length > 0) {
                    var childs = $(evolRoot.params.element).children('li');
                    /* Insert to the first item */
                    if (ind <= 0) {
                        $(childs[0]).before($(ele));
                    } else if (ind >= childs.length) {
                        $(childs[childs.length]).after($(ele));
                    } else {
                        $(childs[(ind - 1)]).before($(ele));
                    }
                    this.updatePositionForAllProducts();
                }
            };

            /**
             * updatePositionForAllProducts
             * Update position for all product
             * @return null
             */
            this.updatePositionForAllProducts = function() {
                if ('params' in evolRoot && 'element' in evolRoot.params && evolRoot.params.element != '' && $(evolRoot.params.element).length > 0) {
                    var childs = $(evolRoot.params.element).children('li'),
                        that = this;
                    if (childs.length > 0) {
                        $.each(childs, function(ind, item) {
                            that.itemUpdate(item, (ind + 1));
                        }).promise().done(function() {
                            that.save();
                        });
                    }
                }
            };

            /**
             * save
             * The items save position before post
             * @return null
             */
            this.save = function() {
                if ($('#merchandiser_in_category_products').length) {
                    $('#merchandiser_in_category_products').val(JSON.stringify(this.items));
                }
            };

            /**
             * loading
             * @param boolean i
             * true: show | false: hiden
             * @return null
             */
            this.loading = function(i) {
                i = typeof i == 'undefined' ? false : true;
                if (!$('div.loading-wrapper').length) { $('body').append('<div class="loading-wrapper"><div class="popup "><div class="spinner spinner-1"></div></div></div>'); }
                if (i) { $('div.loading-wrapper').show(); } else { $('div.loading-wrapper').hide(); }
            };

            return this;
        };
        t.evolParams = this.params;
        t.evolFn = this.func();
        t.evolFn.initialize();
        return t;
    };
    $.fn.evolMerchandiser = function(dragula, opts) {
        return this.each(function() {
            $.evolveMerchandiser(this, dragula, opts);
        });
    };
})(jQuery);
