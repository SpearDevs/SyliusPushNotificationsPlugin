/* eslint-disable-next-line */
self.addEventListener('push', (event) => {
  if (event && event.data) {
    /* eslint-disable-next-line */
    self.pushData = event.data;

    /* eslint-disable-next-line */
    if (self.pushData) {
      /* eslint-disable-next-line */
      const notificationData = self.pushData.json();

      event.waitUntil(
        /* eslint-disable-next-line */
        self.registration.showNotification(notificationData.title, notificationData.options),
      );
    }
  }
});
