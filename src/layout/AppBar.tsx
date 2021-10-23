import {forwardRef} from 'react';
import {AppBar, MenuItemLink, useRedirect, UserMenu} from 'react-admin';
import Typography from '@material-ui/core/Typography';
import SettingsIcon from '@material-ui/icons/Settings';
import {makeStyles} from '@material-ui/core/styles';
import {useSelector} from "react-redux";
import {AppState} from "../types";
import Button from "@material-ui/core/Button";

const useStyles = makeStyles({
    title: {
        flex: 1,
        textOverflow: 'ellipsis',
        whiteSpace: 'nowrap',
        overflow: 'hidden',
    },
    spacer: {
        flex: 1,
    },
});

const ConfigurationMenu = forwardRef<any, any>((props, ref) => {
    return (
        <MenuItemLink
            ref={ref}
            to="/configuration"
            primaryText="Настройки"
            leftIcon={<SettingsIcon/>}
            onClick={props.onClick}
            sidebarIsOpen
        />
    );
});

const CustomUserMenu = (props: any) => (
    <UserMenu {...props}>
        <ConfigurationMenu/>
    </UserMenu>
);

const CustomAppBar = (props: any) => {
    const classes = useStyles();
    const tenant = useSelector((state: AppState) => state.tenant)
    const redirect = useRedirect();

    return (
        <AppBar {...props} elevation={1} userMenu={<CustomUserMenu/>}>
            <Typography
                variant="h6"
                color="inherit"
                className={classes.title}
                id="react-admin-title"
            />
            <span className={classes.spacer}/>
            {tenant && <Button onClick={() => redirect('/switch')}>{tenant.name}</Button>}
        </AppBar>
    );
};

export default CustomAppBar;
