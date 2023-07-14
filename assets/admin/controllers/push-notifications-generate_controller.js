import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    receiverUserValue = 'user';

    formComponents = {
        receiver: {
            selector: '[data-form-field=receiver]',
            type: 'Input',
        },
        sendTo: {
            selector: '[data-form-row=send-to]',
            type: 'Row',
        },
        user: {
            selector: '[data-form-row=user]',
            type: 'Row',
        },
    }

    connect = () => {
        const form = this.element;
        this.receiverInput = form.querySelector(this.formComponents.receiver.selector);
        this.sendToRow = form.querySelector(this.formComponents.sendTo.selector);
        this.userRow = form.querySelector(this.formComponents.user.selector);

        if (!this.validateElements()) {
            return;
        }

        this.handleValueChange();

        this.receiverInput.addEventListener('input', () => {
            this.handleValueChange();
        });
    }

    validateElements = () => {
        return Object.keys(this.formComponents).map(key => {
            const component = this.formComponents[key];

            if (!this[`${key}${component.type}`]) {
                console.warn(`Missing ${component.type} with '${component.selector}' attribute.`)

                return false;
            }

            return true;
        }).every(value => value === true);
    }

    handleValueChange = () => {
        this.sendToRow.style.display = this.receiverInput.value === this.receiverUserValue ? 'none' : 'block';
        this.userRow.style.display = this.receiverInput.value === this.receiverUserValue ? 'block' : 'none';
    }
}
