import { store } from '@wordpress/interactivity';

/**
 * Interactivity API Store for the Announcement Bar
 */
store('modernCoreAnnouncement', {
    state: {
        get isOpen() {
            return true;
        }
    },
    actions: {
        dismiss: (context) => {
            // Update the context state to false, which triggers the DOM bindings
            context.state.isOpen = false;
        }
    }
});