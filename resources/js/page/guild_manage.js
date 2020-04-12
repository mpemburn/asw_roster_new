/* Specific to the guild manager page */

$(document).ready(function ($) {
    if ($('#guild_manage').is('*')) {
        var guild = appSpace.urlQuery.getUrlPart();
        var guildTable = Object.create(TableManager);
        guildTable.init({
            typeaheadUrl: appSpace.baseUrl + '/member/search?q=%QUERY&guild_id=' + guild,
            addUrl: appSpace.baseUrl + '/guild/add?guild_id=' + guild,
            removeUrl: appSpace.baseUrl + '/guild/remove?guild_id=' + guild,
            idName: 'member_id',
            tableSelector: '#guild_member_list',
            searchSelector: '#guild_search',
            addSelector: '#guild_add_member',
            removeSelector: '.guild-remove',
            canEdit: appSpace.canEdit,
            timeoutMessage: appSpace.authTimeout,
            onTableComplete: function() {
                // Retrieve coven names into select via AJAX
                var reviseSelect = Object.create(ReviseSelect);
                reviseSelect.init({
                    ajaxUrl: '/public/member/covens',
                    selector: '[aria-label^="Coven"]',
                    width: '75px',
                    isChild: true,
                    prepend: {value: '', text: 'Coven'},
                    useOriginalValues: true
                });
            }
        });
    }
});
