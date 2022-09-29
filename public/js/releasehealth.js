/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

/**
 * Implement the Firefox Release Health Dashboard.
 */
class ReleaseHealth {
  /**
   * Initialize a new `ReleaseHealth` instance.
   */
  constructor() {
    this.params = new URLSearchParams(location.search);
    this.loadConfig();
  }

  /**
   * Fetch a remote JSON file and return the results.
   * @param {String} url URL to fetch.
   * @returns {Promise.<Object>} Decoded JSON.
   */
  async getJSON(url) {
    return fetch(url).then(response => response.json());
  }

  /**
   * Load the configuration file
   */
  async loadConfig() {
    this.config = await this.getJSON('js/bzconfig.json');
    this.renderUI();
  }

  /**
   * Start rendering the UI.
   */
  renderUI() {
    this.displayMeasures();
    this.getBugCounts();

    // Update counts periodically
    window.setInterval(() => this.getBugCounts(), this.config.refreshMinutes * 60 * 1000);
  }

  /**
   * Display the measures with a placeholder.
   */
  displayMeasures() {
    for (let { id } of this.config.bugQueries) {
      document.querySelector(`#${id}`).innerHTML =
        `&nbsp;<span class="data badge text-bg-secondary float-end">?</span>`;
    }
  }

  /**
   * Fetch the number of bugs for all the queries.
   */
  getBugCounts() {
    for (const query of this.config.bugQueries) {
      // Use an inner `async` so the loop continues
      (async () => {
        const { bug_count } = await this.getJSON(`${this.config.BUGZILLA_REST_URL}${query.url}&count_only=1`);

        if (bug_count !== undefined) {
          query.count = bug_count;
          this.displayCount(query);
        }
      })();
    }
  }

  /**
   * Display the number of bugs for a query.
   * @param {String} id Element ID.
   * @param {Number} count Number of bugs.
   */
  displayCount({ id, count }) {
    const $placeholder = document.querySelector(`#${id} .data`);

    $placeholder.textContent = count;
    $placeholder.classList.remove('text-bg-secondary');
    $placeholder.classList.add('text-bg-primary');

    if (count === 0) {
      $placeholder.classList.add('d-none');
    }
  }

};

window.addEventListener('DOMContentLoaded', () => new ReleaseHealth(), { once: true });
