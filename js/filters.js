var LeverFilters = {
    init: function () {
        jQuery('.lever-filters .filter').on('click', function () {
            LeverFilters.toggleFilterOptions(jQuery(this));
        });

        jQuery(document).on('mouseup', function (e) {
            var container = jQuery('.lever-filters .filter');
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                LeverFilters.toggleOffFilters();
            }
        });
    },
    toggleFilterOptions: function (element) {
        var hasClass = element.find('.filter-values').hasClass('active');

        LeverFilters.toggleOffFilters();
        if (hasClass) {
            element.find('.filter-values').removeClass('active');
            return;
        }

        element.find('.filter-values').addClass('active');
    },
    toggleOffFilters: function () {
        jQuery('.lever-filters .filter-values.active').removeClass('active');
    }
};

LeverFilters.init();