// External Dependencies
import React, { Component, Fragment } from 'react';

// Internal Dependencies
import './style.css';


class BNPollOfTheDay extends Component {

  static slug = 'bnpotd_bnpolloftheday'

  render() {
    //console.log( this );
    //console.log( this.props );
    return (
      <Fragment>
        <h1>
          {this.props.moduleInfo.type}
        </h1>
      </Fragment>
    );
  }
}

export default BNPollOfTheDay;
