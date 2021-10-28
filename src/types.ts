import {Identifier, Record, ReduxState} from 'react-admin';

export type ThemeName = 'light' | 'dark';

export type UUID = string;

export interface TenantState {
    id: UUID,
    identifier: string,
    name: string,
    group: {
        id: UUID,
        name: string,
        identifier: string,
    },
}

export interface AppState extends ReduxState {
    theme: ThemeName;
    tenant: TenantState,
}

export interface Timestampable {
    updated_at: string,
    created_at: string,
}

export interface Manufacturer extends Record, Timestampable {
    name: string,
    localized_name: string,
}

export interface Vehicle extends Record, Timestampable {
    name: string,
    localized_name: string,
    manufacturer_id: Identifier,
    case_name: string,
    year_from: number,
    year_till: number,
}

export interface Part extends Record {
    name: string,
    number: string,
    manufacturer_id: Identifier,
}