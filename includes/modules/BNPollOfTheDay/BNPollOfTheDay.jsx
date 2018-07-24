// External Dependencies
import React, { Component, Fragment } from 'react';

// Internal Dependencies
import './style.css';


class BNPollOfTheDay extends Component {

  static slug = 'bnpotd_bnpolloftheday';

  render() {
    return (
      <Fragment>
        <h1>{this.testStr}</h1>
        <h1>
          {this.props.content()}
        </h1>
      </Fragment>
    );
  }
}

export default BNPollOfTheDay;
