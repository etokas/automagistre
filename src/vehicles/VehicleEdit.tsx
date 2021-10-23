import {
    AutocompleteInput,
    Edit,
    EditProps,
    NumberInput,
    ReferenceInput,
    required,
    SimpleForm,
    TextInput
} from 'react-admin';
import {Vehicle} from '../types';

interface VehicleTitleProps {
    record?: Vehicle;
}

const VehicleTitle = ({record}: VehicleTitleProps) => record ? <span>Кузов {record.name}</span> : null;

const VehicleEdit = (props: EditProps) => {
    return (
        <Edit {...props} title={<VehicleTitle/>}>
            <SimpleForm>
                <ReferenceInput
                    source="manufacturer_id"
                    reference="manufacturer"
                    label="Производитель"
                    validate={required()}
                    filterToQuery={searchText => ({"name,localized_name": searchText})}
                >
                    <AutocompleteInput optionText="name" source="name"/>
                </ReferenceInput>
                <TextInput
                    source="name"
                    validate={required()}
                    label="Название"
                />
                <TextInput
                    source="localized_name"
                    label="Название на русском"
                />
                <TextInput
                    source="case_name"
                    label="Кузов"
                />
                <NumberInput
                    source="year_from"
                    label="Начало производства"
                />
                <NumberInput
                    source="year_till"
                    label="Конец производства"
                />
            </SimpleForm>
        </Edit>
    );
};

export default VehicleEdit;
