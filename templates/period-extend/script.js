BX.ready(function() {
    function initPeriod() {
        /**********Œ“ –€“»≈-«¿ –€“»≈ “¿¡À»÷€ — –¿—ÿ»‘–Œ¬ Œ… œ≈–»Œƒ¿ start *************/
        $('.charges-history__period_with_debt .charges-history__period').click(function () {
            var elementTr = $(this).parent();
            if (elementTr.hasClass('charges-history__period-visible'))
                elementTr.removeClass('charges-history__period-visible');
            else
                elementTr.addClass('charges-history__period-visible');
        });

        $('.history-mobi__period .history-mobi__block').click(function () {
            var elementTr = $(this).parent();
            if (elementTr.hasClass('charges-history__period-visible'))
                elementTr.removeClass('charges-history__period-visible');
            else
                elementTr.addClass('charges-history__period-visible');
        });

        $('.history-mobi__item-main .history-mobi__name-header').click(function () {
            var elementTr = $(this).parent();
            if (elementTr.hasClass('history-mobi__visible'))
                elementTr.removeClass('history-mobi__visible');
            else
                elementTr.addClass('history-mobi__visible');
        });

        $('.charges-history__services-close').click(function () {
            $(this).parent().parent().parent().prev('tr').last().removeClass('charges-history__period-visible');
        });
        /**********Œ“ –€“»≈-«¿ –€“»≈ “¿¡À»÷€ — –¿—ÿ»‘–Œ¬ Œ… œ≈–»Œƒ¿ end *************/
        
        var chargesHistory = $(".charges-history__table");
        var table = chargesHistory.find("table").first();
        var historyMobi = $(".history-mobi");
        function resize(){
            chargesHistory.show();
            if(chargesHistory.width() < table.width()){
                historyMobi.show();
                chargesHistory.hide();
            }
            else{
                historyMobi.hide();
            }
        }
        $(window).resize(function(){
            resize();
        });
        resize();
    }
    BX.addCustomEvent('onAjaxSuccess', function(){
        $('.charges-history__period_with_debt .charges-history__period').unbind('click');
        $('.charges-history__services-close').unbind('click');
        initPeriod();
    });
    initPeriod();


});