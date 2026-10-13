/**
 * Admin JavaScript for NIESV Theme
 * Handles character counting for ACF fields
 */

jQuery(document).ready(function($) {
    
    // Character counting for Mission, Vision, Values fields
    function initCharacterCounting() {
        const fieldsToCount = [
            'mission_statement',
            'vision_statement', 
            'core_values'
        ];
        
        fieldsToCount.forEach(function(fieldName) {
            const field = $('[data-name="' + fieldName + '"] textarea');
            if (field.length) {
                addCharacterCounter(field, 200);
            }
        });
    }
    
    function addCharacterCounter(field, maxLength) {
        const fieldWrapper = field.closest('.acf-input');
        
        // Create counter element
        const counter = $('<div class="character-counter" style="text-align: right; font-size: 12px; color: #666; margin-top: 5px;"></div>');
        fieldWrapper.append(counter);
        
        // Update counter function
        function updateCounter() {
            const currentLength = field.val().length;
            const remaining = maxLength - currentLength;
            
            let counterText = currentLength + '/' + maxLength + ' characters';
            let counterColor = '#666';
            
            if (remaining < 20) {
                counterColor = '#d63638'; // Red when close to limit
                counterText += ' (Warning: ' + remaining + ' remaining)';
            } else if (remaining < 50) {
                counterColor = '#dba617'; // Yellow when getting close
                counterText += ' (' + remaining + ' remaining)';
            }
            
            if (currentLength > maxLength) {
                counterColor = '#d63638';
                counterText = 'Exceeded by ' + (currentLength - maxLength) + ' characters!';
                field.css('border-color', '#d63638');
            } else {
                field.css('border-color', '');
            }
            
            counter.text(counterText).css('color', counterColor);
        }
        
        // Initial count
        updateCounter();
        
        // Update on input
        field.on('input keyup paste', updateCounter);
    }
    
    // Initialize on page load
    initCharacterCounting();
    
    // Re-initialize when ACF adds new fields (for repeaters, etc.)
    $(document).on('acf/setup_fields', function(e, postbox) {
        initCharacterCounting();
    });
    
    // Add visual enhancements for About page fields
    function enhanceAboutPageFields() {
        // Add helpful descriptions
        const fieldDescriptions = {
            'mvv_main_title': 'Main heading for the Mission/Vision/Values section (e.g., "Our Foundation", "Who We Are")',
            'mvv_main_subtitle': 'Brief description that appears below the main heading to introduce the section.',
            'mission_title': 'Title for the Mission card (e.g., "Our Mission", "What We Do")',
            'mission_statement': 'Describe your organization\'s purpose and primary objectives. Keep it concise and impactful.',
            'mission_link': 'Optional: Add a link to a detailed page about your mission.',
            'vision_title': 'Title for the Vision card (e.g., "Our Vision", "Where We\'re Going")',
            'vision_statement': 'Share your long-term aspirations and what you hope to achieve in the future.',
            'vision_link': 'Optional: Add a link to a detailed page about your vision.',
            'values_title': 'Title for the Values card (e.g., "Our Values", "What We Believe")',
            'core_values': 'List the fundamental principles and beliefs that guide your organization\'s actions.',
            'values_link': 'Optional: Add a link to a detailed page about your values.',
            'community_image': 'Upload an image that represents your community engagement and advocacy work.',
            'community_image_alt': 'Describe the image for screen readers and accessibility (e.g., "NIESV members meeting with community leaders")',
            'community_button_text': 'Text for the call-to-action button (e.g., "Learn More About Our Community Work")',
            'community_button_link': 'URL where the community button should link to.'
        };
        
        Object.keys(fieldDescriptions).forEach(function(fieldName) {
            const field = $('[data-name="' + fieldName + '"]');
            if (field.length && !field.find('.field-description').length) {
                const description = $('<p class="field-description" style="font-style: italic; color: #666; margin-top: 5px; font-size: 13px;">' + fieldDescriptions[fieldName] + '</p>');
                field.find('.acf-input').append(description);
            }
        });
    }
    
    // Initialize field enhancements
    enhanceAboutPageFields();
    
    // Re-initialize when ACF adds new fields
    $(document).on('acf/setup_fields', function(e, postbox) {
        enhanceAboutPageFields();
    });
    
    // Add preview functionality for Mission, Vision, Values
    function addPreviewFunctionality() {
        const previewFields = ['mission_statement', 'vision_statement', 'core_values'];
        
        previewFields.forEach(function(fieldName) {
            const field = $('[data-name="' + fieldName + '"] textarea');
            if (field.length) {
                const fieldWrapper = field.closest('.acf-input');
                const previewButton = $('<button type="button" class="button button-secondary" style="margin-top: 10px;">Preview</button>');
                
                previewButton.on('click', function(e) {
                    e.preventDefault();
                    const content = field.val();
                    if (content.trim()) {
                        const modal = $('<div class="preview-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 999999; display: flex; align-items: center; justify-content: center;"></div>');
                        const modalContent = $('<div style="background: white; padding: 30px; border-radius: 8px; max-width: 500px; max-height: 80vh; overflow-y: auto; position: relative;"><h3 style="margin-top: 0; text-transform: capitalize;">' + fieldName.replace('_', ' ') + ' Preview</h3><p style="line-height: 1.6;">' + content + '</p><button type="button" class="button button-primary close-preview" style="margin-top: 20px;">Close</button></div>');
                        
                        modal.append(modalContent);
                        $('body').append(modal);
                        
                        modal.on('click', function(e) {
                            if (e.target === modal[0]) {
                                modal.remove();
                            }
                        });
                        
                        modalContent.find('.close-preview').on('click', function() {
                            modal.remove();
                        });
                    }
                });
                
                fieldWrapper.append(previewButton);
            }
        });
    }
    
    // Initialize preview functionality
    addPreviewFunctionality();
});
