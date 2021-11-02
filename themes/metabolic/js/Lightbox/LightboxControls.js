/*
* Container for display and editing of applied browse filters. This component provides
* markup wrapping both browse statistics (# of results found) (component <LightboxStatistics>
* as well as the list of available browse facets (component <LightboxFacetList>).
*
* Props are:
* 		facetLoadUrl : URL to use to load facet content
*
* Sub-components are:
* 		LightboxStatistics
* 		LightboxDragAndDrop
* 		LightboxSelection
* 		LightboxSortOptions
* 		LightboxExportOptions
*
* Used by:
*  	Lightbox
*
* Uses context: LightboxContext
*/

import React, { useContext } from 'react'
import { LightboxContext } from './LightboxContext'

import LightboxExportOptions from './LightboxControls/LightboxExportOptions';
import LightboxSortOptions from './LightboxControls/LightboxSortOptions';
import LightboxStatistics from './LightboxControls/LightboxStatistics';
import LightboxDragAndDrop from './LightboxControls/LightboxDragAndDrop';
import LightboxSelection from './LightboxControls/LightboxSelection';

const LightboxControls = () => {
	
	const { id, setId, tokens, setTokens, userAccess, setUserAccess } = useContext(LightboxContext)

	return (
		<div className="row mb-3">
			<div className="col-4 ">
				<LightboxStatistics />
			</div>

			<div className="col-8 ">
				<div className='row d-flex align-items-center justify-content-end'>
					<LightboxDragAndDrop />
					<LightboxSelection />
					<LightboxSortOptions />
					<LightboxExportOptions />
				</div>
			</div>
		</div>
	)
}

export default LightboxControls



// import React from "react";
// import ReactDOM from "react-dom";
// import { LightboxContext } from '../Lightbox';

// import LightboxViewList from './LightboxControls/LightboxViewList';
// import LightboxExportOptions from './LightboxControls/LightboxExportOptions';
// import LightboxSortOptions from './LightboxControls/LightboxSortOptions';
// import LightboxStatistics from './LightboxControls/LightboxStatistics';
// import LightboxDragAndDrop from './LightboxControls/LightboxDragAndDrop';
// import LightboxSelection from './LightboxControls/LightboxSelection';


// class LightboxControls extends React.Component {

// 	constructor(props) {
// 		super(props);

//     	LightboxControls.contextType = LightboxContext;

// 	}

// 	render() {
// 		let c  = (this.context.state.resultSize === null);
// 		// console.log('Context', this.context);
// 		return(
// 			<div className="row">
// 				<div className="col-md-6">
// 					<LightboxStatistics/>
// 				</div>

// 				<div className="col-md-6">
// 					<div className='row'>
// 						<LightboxDragAndDrop/>
// 						<LightboxSelection/>
// 						{/*<LightboxSelectItemsOptions/>*/}
// 						<LightboxSortOptions/>
// 						<LightboxExportOptions/>
// 					</div>

// 					{/* view doesn't work yet
// 					<LightboxViewList/>
// 					*/}


//           {/*

//             // TODO: Get the button functionality for saving a sort by field working in this component.

//           {this.context.state.showSaveButton == true ?
//             <div>
//               <button type="button" className="btn btn-secondary" onClick={() => this.saveFromSortOptions(resultList)} style={{marginLeft: '6px'}}> Save Sort Permanently</button>
//               <button type="button" className="btn btn-danger" onClick={() => this.cancelSaveFromSortOptions} style={{marginLeft: '6px'}}>Cancel</button>
//             </div>
//             :
//             ' '
//           }
//           */}

// 				</div>
// 			</div>
// 		);
// 	}
// }

// export default LightboxControls;
