import { Controller } from '@hotwired/stimulus';
import Webpush from 'webpush-client';

export default class extends Controller {
    static values = {
        publicKey: {
            type: String,
            default: ''
        },
        serviceWorkerPath: {
            type: String,
            default: ''
        },
        subscribeUrl: {
            type: String,
            default: '/webpush'
        },
    };

    buttonAttribute = 'data-notifications-status';

    subscriptionStates = {
        granted: 'granted',
        denied: 'denied',
        default: 'default'        
    };

    connect = async () => {
        if ('' === this.publicKey) {
            return console.warn('No publicKey argument passed to controller');
        }

        if (
            !'Notification' in window
            || !'serviceWorker' in navigator
        ) {
            return console.warn('Browser doesn\'t support push notifications.');
        }

        this.prepareButtons();

        if (!this.buttondefault) {
            return console.warn('Subscription trigger button not present in DOM.');
        }

        this.buttondefault.addEventListener('click', this.askForNotificationPermission);

        const notificationPermission = Notification.permission;

        switch (notificationPermission) {
            case this.subscriptionStates.default:
                this.showButton(this.subscriptionStates.default);

                break;
            
            case this.subscriptionStates.denied:
                this.showButton(this.subscriptionStates.denied);
                
                break;
            
            case this.subscriptionStates.granted:
                this.configurePushSubscription();
                this.showButton(this.subscriptionStates.granted);

                break;
        }
    }

    prepareButtons = () => {
        Object.values(this.subscriptionStates).forEach((state) => {
            this[`button${state}`] = document.querySelector(`[${this.buttonAttribute}="${state}"]`);
        });
    }

    showButton = (activeState) => {
        Object.values(this.subscriptionStates).forEach((state) => {
            if (activeState === state) {
                return this[`button${state}`].style.display = 'inline-block';
            }

            return this[`button${state}`].style.display = 'none';
        });
    }

    askForNotificationPermission = () => {
        Notification.requestPermission((result) => {
            if (result === this.subscriptionStates.granted) {
                this.configurePushSubscription();
            }

            this.showButton(result);
        });
    }

    configurePushSubscription = async () => {
        const serviceWorker = await navigator.serviceWorker.ready;
        const pushSubscription = await serviceWorker.pushManager.getSubscription();

        if (pushSubscription !== null) {
            return;
        }

        try {
            const webPushClient = await Webpush.create({
                serviceWorkerPath: this.serviceWorkerPathValue,
                serverKey: this.publicKeyValue,
                subscribeUrl: this.subscribeUrlValue,
            });

            await webPushClient.subscribe();
        } catch (error) {
            this.showButton(this.subscriptionStates.default);
            console.error(error);
        }
    }
}
