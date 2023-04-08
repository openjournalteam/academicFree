$(document).ready(function() {
    fixMakeSubmissionBlock();
    
    function fixMakeSubmissionBlock() {
        const block = $('.pkp_block.block_make_submission');

        if (block.length) {
            const blockTitle = block.find('h2.pkp_screen_reader');

            if (blockTitle.length) {
                blockTitle.addClass('title');
            }
        }
    }
});