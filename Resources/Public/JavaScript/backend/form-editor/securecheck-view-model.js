/**
 * Module: @websitemensch/wsm-form-spamshield/backend/form-editor/securecheck-view-model.js
 */


/**
 * @private
 *
 * @return object
 */
function getPublisherSubscriber(formEditorApp) {
    return formEditorApp.getPublisherSubscriber();
}

/**
 * @private
 *
 * @return void
 */
function subscribeEvents(formEditorApp) {
    /**
     * @private
     *
     * @param string
     * @param array
     *              args[0] = formElement
     *              args[1] = template
     * @return void
     */
    getPublisherSubscriber(formEditorApp).subscribe('view/stage/abstract/render/template/perform', function (topic, args) {
        if (args[0].get('type') === 'SecureCheck') {
            formEditorApp.viewModel.getStage().renderSimpleTemplateWithValidators(args[0], args[1]);
        }
    });
}
export function bootstrap(formEditorApp) {
    subscribeEvents(formEditorApp);
}