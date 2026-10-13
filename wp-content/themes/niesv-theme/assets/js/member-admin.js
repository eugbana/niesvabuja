/**
 * Member Area Admin JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize modal
    $('#add-member-modal').dialog({
        autoOpen: false,
        modal: true,
        width: 600,
        height: 'auto',
        resizable: false,
        buttons: {
            'Add Member': function() {
                $('#add-member-form').submit();
            },
            'Cancel': function() {
                $(this).dialog('close');
            }
        }
    });
    
    // Open add member modal
    $('#add-new-member').on('click', function(e) {
        e.preventDefault();
        $('#add-member-form')[0].reset();
        $('#add-member-modal').dialog('open');
    });
    
    // Handle add member form submission
    $('#add-member-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: niesvMemberAdmin.ajaxurl,
            type: 'POST',
            data: formData + '&action=niesv_add_member&nonce=' + niesvMemberAdmin.nonce,
            beforeSend: function() {
                $('#add-member-modal').find('.ui-dialog-buttonpane button').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    $('#add-member-modal').dialog('close');
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('An error occurred while adding the member.');
            },
            complete: function() {
                $('#add-member-modal').find('.ui-dialog-buttonpane button').prop('disabled', false);
            }
        });
    });
    
    // Handle individual member deletion
    $('.delete-member').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm(niesvMemberAdmin.confirmDelete)) {
            return;
        }
        
        var memberId = $(this).data('member-id');
        var $row = $(this).closest('tr');
        
        $.ajax({
            url: niesvMemberAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'niesv_delete_member',
                member_id: memberId,
                nonce: niesvMemberAdmin.nonce
            },
            beforeSend: function() {
                $row.addClass('deleting');
            },
            success: function(response) {
                if (response.success) {
                    $row.fadeOut(300, function() {
                        $(this).remove();
                        updateMemberCount();
                    });
                } else {
                    alert('Error: ' + response.data);
                    $row.removeClass('deleting');
                }
            },
            error: function() {
                alert('An error occurred while deleting the member.');
                $row.removeClass('deleting');
            }
        });
    });
    
    // Handle bulk actions
    $('#doaction').on('click', function(e) {
        e.preventDefault();
        
        var action = $('#bulk-action-selector-top').val();
        if (action === '-1') {
            alert('Please select an action.');
            return;
        }
        
        var selectedMembers = [];
        $('input[name="member[]"]:checked').each(function() {
            selectedMembers.push($(this).val());
        });
        
        if (selectedMembers.length === 0) {
            alert('Please select at least one member.');
            return;
        }
        
        var confirmMessage = action === 'delete' ? 
            niesvMemberAdmin.confirmBulkDelete : 
            'Are you sure you want to perform this action on the selected members?';
            
        if (!confirm(confirmMessage)) {
            return;
        }
        
        $.ajax({
            url: niesvMemberAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'niesv_bulk_action_members',
                bulk_action: action,
                member_ids: selectedMembers,
                nonce: niesvMemberAdmin.nonce
            },
            beforeSend: function() {
                $('#doaction').prop('disabled', true).val('Processing...');
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('An error occurred while processing the bulk action.');
            },
            complete: function() {
                $('#doaction').prop('disabled', false).val('Apply');
            }
        });
    });
    
    // Handle select all checkbox
    $('#cb-select-all-1').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('input[name="member[]"]').prop('checked', isChecked);
    });
    
    // Update select all checkbox when individual checkboxes change
    $('input[name="member[]"]').on('change', function() {
        var totalCheckboxes = $('input[name="member[]"]').length;
        var checkedCheckboxes = $('input[name="member[]"]:checked').length;
        
        $('#cb-select-all-1').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
    
    // Live search functionality
    var searchTimeout;
    $('#member-search-form input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        var searchTerm = $(this).val();
        
        if (searchTerm.length >= 3 || searchTerm.length === 0) {
            searchTimeout = setTimeout(function() {
                performLiveSearch(searchTerm);
            }, 500);
        }
    });
    
    function performLiveSearch(searchTerm) {
        $.ajax({
            url: niesvMemberAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'niesv_search_members',
                search: searchTerm,
                nonce: niesvMemberAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateMemberTable(response.data);
                }
            }
        });
    }
    
    function updateMemberTable(members) {
        var $tbody = $('.wp-list-table tbody');
        $tbody.empty();
        
        if (members.length === 0) {
            $tbody.append('<tr><td colspan="8" class="no-items">No members found.</td></tr>');
            return;
        }
        
        $.each(members, function(index, member) {
            var row = '<tr data-member-id="' + member.id + '">' +
                '<th class="check-column"><input type="checkbox" name="member[]" value="' + member.id + '"></th>' +
                '<td class="column-membership-no"><strong>' + member.membership_no + '</strong></td>' +
                '<td class="column-name">' +
                    '<strong><a href="post.php?post=' + member.id + '&action=edit">' + member.full_name + '</a></strong>' +
                    '<div class="row-actions">' +
                        '<span class="edit"><a href="post.php?post=' + member.id + '&action=edit">Edit</a> |</span>' +
                        '<span class="trash"><a href="#" class="delete-member" data-member-id="' + member.id + '">Delete</a></span>' +
                    '</div>' +
                '</td>' +
                '<td class="column-email">' + member.email + '</td>' +
                '<td class="column-category">-</td>' +
                '<td class="column-branch">-</td>' +
                '<td class="column-status">-</td>' +
                '<td class="column-actions">' +
                    '<a href="post.php?post=' + member.id + '&action=edit" class="button button-small">Edit</a> ' +
                    '<a href="#" class="button button-small delete-member" data-member-id="' + member.id + '">Delete</a>' +
                '</td>' +
                '</tr>';
            $tbody.append(row);
        });
        
        // Re-bind event handlers for new elements
        bindEventHandlers();
    }
    
    function bindEventHandlers() {
        $('.delete-member').off('click').on('click', function(e) {
            e.preventDefault();
            
            if (!confirm(niesvMemberAdmin.confirmDelete)) {
                return;
            }
            
            var memberId = $(this).data('member-id');
            var $row = $(this).closest('tr');
            
            $.ajax({
                url: niesvMemberAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'niesv_delete_member',
                    member_id: memberId,
                    nonce: niesvMemberAdmin.nonce
                },
                beforeSend: function() {
                    $row.addClass('deleting');
                },
                success: function(response) {
                    if (response.success) {
                        $row.fadeOut(300, function() {
                            $(this).remove();
                            updateMemberCount();
                        });
                    } else {
                        alert('Error: ' + response.data);
                        $row.removeClass('deleting');
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the member.');
                    $row.removeClass('deleting');
                }
            });
        });
        
        $('input[name="member[]"]').off('change').on('change', function() {
            var totalCheckboxes = $('input[name="member[]"]').length;
            var checkedCheckboxes = $('input[name="member[]"]:checked').length;
            
            $('#cb-select-all-1').prop('checked', totalCheckboxes === checkedCheckboxes);
        });
    }
    
    function updateMemberCount() {
        var currentCount = $('.wp-list-table tbody tr').length;
        if (currentCount === 0) {
            $('.wp-list-table tbody').html('<tr><td colspan="8" class="no-items">No members found.</td></tr>');
        }
    }
    
    // Form validation
    $('#add-member-form').on('submit', function(e) {
        var membershipNo = $('#membership_no').val().trim();
        var fullName = $('#full_name').val().trim();
        var email = $('#email_address').val().trim();
        var category = $('#category').val();
        var branch = $('#branch').val();
        
        if (!membershipNo || !fullName || !email || !category || !branch) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        // Email validation
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email address.');
            return false;
        }
    });
});
