document.addEventListener("DOMContentLoaded", function () {
    const $enableDangerousActionForm = $('#enable-dangerous-action-form')
    $enableDangerousActionForm.on('submit', (e) => {
        e.preventDefault()
        const dangerousKey = $enableDangerousActionForm.find("input[name='key']").val()
        $('.dangerous-action-key-value').each((index, el) => {
            $(el).val(dangerousKey)
        })
        $('.dangerous-action-button').removeAttr("disabled")
    });

    const $copyLinkButton = $('#copyLinkButton')
    if ($copyLinkButton) {
        navigator.permissions.query({name: "clipboard-write"}).then((result) => {
            if (result.state === "granted" || result.state === "prompt") {
                $copyLinkButton.on('click', () => {
                    navigator.clipboard.writeText(document.getElementById('appLinkText').innerText);
                    $copyLinkButton.val('Copied!')
                    setTimeout(() => {
                        $copyLinkButton.val('Copy Link')
                    }, 2000)
                })

                $copyLinkButton.show()
            }
        });
    }

    const $translationsTable = $('#translationsTable')
    $translationsTable.find('thead th').each(function (i, el) {
        $(el).click(function(e) {
            const columnNumber = $(e.target).index() + 1
            const columnName = $(e.target).text();
            $('#hiddenFieldsTitle').removeClass('d-none')
            $('.hidden-fields:contains("' + columnName + '")').removeClass('d-none')
            $translationsTable.find('th:nth-child(' + columnNumber + '), td:nth-child(' + columnNumber + ')').addClass('d-none')
            let storedHiddenFields = localStorage.getItem('hiddenFields')
            if (!storedHiddenFields) {
                storedHiddenFields = ''
            }
            storedHiddenFields += columnName + ' '
            localStorage.setItem('hiddenFields', storedHiddenFields)
        })
    })

    $('.hidden-fields').click(function(e) {
        const columnName = $(e.target).addClass('d-none').attr('data-language')
        const column = $translationsTable.find('th:contains("' + columnName + '")')
        column.removeClass('d-none')
        const columnNumber = column.index() + 1
        $translationsTable.find('td:nth-child(' + columnNumber + ')').removeClass('d-none')
        let storedHiddenFields = localStorage.getItem('hiddenFields')
        storedHiddenFields = storedHiddenFields.replace(columnName + ' ', '');
        localStorage.setItem('hiddenFields', storedHiddenFields)
    })

    const storedHiddenFields = localStorage.getItem('hiddenFields')
    if (storedHiddenFields) {
        storedHiddenFields.split(' ').map(function (columnName, i) {
            if(columnName) {
                $('#hiddenFieldsTitle').removeClass('d-none')
                $('.hidden-fields[data-language="' + columnName + '"]').removeClass('d-none')
                const column = $translationsTable.find('th:contains("' + columnName + '")')
                column.addClass('d-none')
                const columnNumber = column.index() + 1
                $translationsTable.find('td:nth-child(' + columnNumber + ')').addClass('d-none')
            }
        })
    }

    $translationsTable.find('tbody textarea').each(function (i, el) {
        $(el).change(function(e) {
            $(e.target).parents('form').submit();
        })
    })
});
